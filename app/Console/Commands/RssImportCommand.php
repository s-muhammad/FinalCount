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
    protected $signature = 'news:import-rss {--sync : Run AI translation synchronously instead of queueing} {--retry-failed : Re-run AI translation for previously failed imports}';

    protected $description = 'Import news items from active RSS feeds and dispatch AI translation';

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
                if (RssImport::where('source_url', $item['link'])->exists()) {
                    $totals['skipped']++;

                    continue;
                }

                try {
                    $news = $this->createNews($feed, $item);
                    RssImport::create([
                        'feed_id' => $feed->id,
                        'news_id' => $news->id,
                        'source_url' => $item['link'],
                        'status' => 'pending',
                        'raw_title' => $item['title'],
                        'imported_at' => $item['pub_date'] ?? now(),
                    ]);

                    if ($this->option('sync')) {
                        (new AiTranslateNewsJob($news))->handle(app(\App\Services\AiService::class));
                        $this->info("  Imported + translated: {$item['title']}");
                    } else {
                        dispatch(new AiTranslateNewsJob($news));
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
        $this->info("Done. Created: {$totals['created']} | Skipped: {$totals['skipped']} | Failed: {$totals['failed']}");

        return self::SUCCESS;
    }

    private function createNews(RssFeed $feed, array $item): News
    {
        $image = $this->storeImage($item['image']);

        $news = new News;
        $news->title = $item['title'];
        $news->summary = Str::limit($item['description'], 250);
        $news->body = $item['description'];
        $news->image = $image;
        $news->slug = $this->uniqueSlug($item['title']);
        $news->status = 'draft';
        $news->published_at = $item['pub_date'];
        $news->save();

        return $news;
    }

    private function retryFailed(): int
    {
        $failed = RssImport::where('status', 'failed')
            ->whereNotNull('news_id')
            ->get();

        if ($failed->isEmpty()) {
            $this->warn('No failed imports to retry.');

            return 0;
        }

        $count = 0;

        foreach ($failed as $import) {
            $news = $import->news;

            if (! $news) {
                continue;
            }

            if ($this->option('sync')) {
                (new AiTranslateNewsJob($news))->handle(app(\App\Services\AiService::class));
                $this->info("Retried (sync): {$import->raw_title}");
            } else {
                dispatch(new AiTranslateNewsJob($news));
                $this->info("Retried (queued): {$import->raw_title}");
            }

            $count++;
        }

        $this->info("Retried {$count} failed import(s).");

        return $count;
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);

        if ($slug === '') {
            $slug = 'news-'.Str::lower(Str::random(6));
        }

        $base = $slug;
        $i = 2;

        while (News::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
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