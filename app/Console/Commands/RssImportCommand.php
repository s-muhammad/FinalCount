<?php

namespace App\Console\Commands;

use App\Jobs\AiTranslateNewsJob;
use App\Models\News;
use App\Models\RssFeed;
use App\Models\RssImport;
use App\Services\RssFetcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class RssImportCommand extends Command
{
    protected $signature = 'news:import-rss {--sync : Run AI translation synchronously instead of queueing} {--retry-failed : Re-run AI translation for failed or pending imports} {--force : Re-translate reader items that lack content}';

    protected $description = 'Import RSS items into the reader queue and translate them';

    public function handle(RssFetcher $fetcher): int
    {
        if ($this->option('retry-failed')) {
            $this->retryFailed();
        }

        $feeds = RssFeed::where('is_active', true)->get();

        if ($feeds->isEmpty()) {
            $this->warn('No active RSS feed configured.');

            return self::SUCCESS;
        }

        $totals = ['created' => 0, 'skipped' => 0, 'failed' => 0];

        foreach ($feeds as $feed) {
            $this->line("Processing feed [{$feed->name}] ({$feed->url})");

            try {
                $items = $fetcher->fetch($feed);
            } catch (Throwable $e) {
                $this->error("  Feed error: {$e->getMessage()}");
                $totals['failed']++;

                continue;
            }

            if (empty($items)) {
                $this->warn('  No items found.');

                continue;
            }

            foreach ($items as $item) {
                $linkHash = sha1($item['link']);

                if (News::where('source_url_hash', $linkHash)->exists()
                    || RssImport::where('source_url_hash', $linkHash)->exists()) {
                    $totals['skipped']++;

                    continue;
                }

                try {
                    $import = RssImport::create([
                        'feed_id' => $feed->id,
                        'source_url' => $item['link'],
                        'source_url_hash' => $linkHash,
                        'status' => 'pending',
                        'raw_title' => $item['title'],
                        'raw_body' => $item['description'],
                        'image' => $this->storeImage($item['image']),
                        'imported_at' => $item['pub_date'] ?? now(),
                    ]);

                    if ($this->option('sync')) {
                        (new AiTranslateNewsJob($import))->handle(app(\App\Services\AiService::class));
                        $this->info("  Imported + translated: {$item['title']}");
                    } else {
                        dispatch(new AiTranslateNewsJob($import));
                        $this->info("  Imported (translation queued): {$item['title']}");
                    }

                    $totals['created']++;
                } catch (Throwable $e) {
                    $this->error("  Item error: {$e->getMessage()}");
                    $totals['failed']++;
                }
            }
        }

        $this->newLine();
        $this->info("Done. Imported: {$totals['created']} | Skipped: {$totals['skipped']} | Failed: {$totals['failed']}");

        return self::SUCCESS;
    }

    private function retryFailed(): int
    {
        $imports = RssImport::whereIn('status', ['failed', 'pending'])->get();

        if ($this->option('force')) {
            $imports = $imports->merge(
                RssImport::where('status', 'translated')
                    ->where(fn ($q) => $q->whereNull('title_en')->orWhere('title_en', ''))
                    ->get()
            )->unique('id');
        }

        if ($imports->isEmpty()) {
            $this->warn('No failed, pending, or untranslated imports to process.');

            return 0;
        }

        $count = 0;

        foreach ($imports as $import) {
            $label = $import->raw_title ?: ($import->source_url ?? ('#'.$import->id));

            if ($this->option('sync')) {
                (new AiTranslateNewsJob($import))->handle(app(\App\Services\AiService::class));
                $done = $import->fresh()->title_en ? 'OK' : 'FAILED';
                $this->info("Processed (sync): {$label} → {$done}");
            } else {
                dispatch(new AiTranslateNewsJob($import));
                $this->info("Queued: {$label}");
            }

            $count++;
        }

        $this->info("Processed {$count} import(s).");

        return $count;
    }

    private function storeImage(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; NewsBot/1.0)'])
                ->get($url);

            if ($response->failed()) {
                return null;
            }

            $ext = $this->guessExtension($url, $response);
            $name = 'news/'.Str::uuid().'.'.$ext;

            Storage::disk('public')->put($name, $response->body());

            return $name;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    private function guessExtension(string $url, \Illuminate\Http\Client\Response $response): string
    {
        $ext = null;

        if (preg_match('/\.(jpe?g|png|webp|gif|avif)(?:[?#]|$)/i', $url, $m)) {
            $ext = strtolower($m[1]);
        }

        if (! $ext) {
            $contentType = $response->header('Content-Type');

            $map = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
                'image/avif' => 'avif',
            ];

            foreach ($map as $mime => $guess) {
                if (str_contains($contentType, $mime)) {
                    $ext = $guess;
                    break;
                }
            }
        }

        return $ext ?: 'jpg';
    }
}