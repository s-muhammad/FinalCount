<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\RssFeed;
use App\Models\RssImport;
use App\Services\RssFetcher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class RssImportCommand extends Command
{
    protected $signature = 'news:import-rss';

    protected $description = "Import today's RSS items into the reader queue (raw, no AI)";

    public function handle(RssFetcher $fetcher): int
    {
        $this->purgeOldImports();

        $feeds = RssFeed::where('is_active', true)->get();

        if ($feeds->isEmpty()) {
            $this->warn('No active RSS feed configured.');

            return self::SUCCESS;
        }

        $totals = ['created' => 0, 'skipped' => 0, 'old' => 0, 'failed' => 0];

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
                $pubDate = $item['pub_date'] ? Carbon::parse($item['pub_date']) : null;

                if ($pubDate && $pubDate->lt(now()->subHours(24))) {
                    $totals['old']++;

                    continue;
                }

                $linkHash = sha1($item['link']);

                if (News::where('source_url_hash', $linkHash)->exists()
                    || RssImport::where('source_url_hash', $linkHash)->exists()) {
                    $totals['skipped']++;

                    continue;
                }

                try {
                    RssImport::create([
                        'feed_id' => $feed->id,
                        'source_url' => $item['link'],
                        'source_url_hash' => $linkHash,
                        'status' => 'pending',
                        'raw_title' => $item['title'],
                        'raw_body' => $item['description'],
                        'image' => $this->storeImage($item['image']),
                        'imported_at' => $item['pub_date'] ?? now(),
                    ]);

                    $this->info("  Imported: {$item['title']}");

                    $totals['created']++;
                } catch (Throwable $e) {
                    $this->error("  Item error: {$e->getMessage()}");
                    $totals['failed']++;
                }
            }
        }

        $this->newLine();
        $this->info("Done. Imported: {$totals['created']} | Skipped (already seen): {$totals['skipped']} | Old (not today): {$totals['old']} | Failed: {$totals['failed']}");

        return self::SUCCESS;
    }

    private function purgeOldImports(): void
    {
        $deleted = RssImport::where('created_at', '<', now()->subHours(24))
            ->whereNull('published_as')
            ->where('status', '!=', 'ignored')
            ->delete();

        if ($deleted > 0) {
            $this->info("Purged {$deleted} old reader item(s) (older than 24h).");
        }
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