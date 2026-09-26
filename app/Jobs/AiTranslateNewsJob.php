<?php

namespace App\Jobs;

use App\Models\RssImport;
use App\Services\AiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AiTranslateNewsJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;

    public $tries = 1;

    public function __construct(public RssImport $rssImport) {}

    public function handle(AiService $ai): void
    {
        if ($this->rssImport->status === 'ignored') {
            return;
        }

        if ($this->rssImport->published_as) {
            $this->rssImport->update([
                'status' => 'translated',
                'error' => null,
            ]);

            return;
        }

        try {
            $rawTitle = $this->rssImport->raw_title ?? '';
            $rawBody = $this->rssImport->raw_body ?? '';

            $result = $ai->translateAndSeo(
                $rawTitle,
                Str::limit($rawBody, 250),
                $rawBody,
            );

            $this->rssImport->update([
                'title' => $result['fa']['title'] ?: $rawTitle,
                'summary' => $result['fa']['summary'] ?: Str::limit($rawBody, 250),
                'body' => $result['fa']['body'] ?: $rawBody,
                'title_ar' => $result['ar']['title'] ?: $rawTitle,
                'summary_ar' => $result['ar']['summary'] ?: Str::limit($rawBody, 250),
                'body_ar' => $result['ar']['body'] ?: $rawBody,
                'title_en' => $result['en']['title'] ?: $rawTitle,
                'summary_en' => $result['en']['summary'] ?: Str::limit($rawBody, 250),
                'body_en' => $result['en']['body'] ?: $rawBody,
                'keywords' => implode(', ', $result['keywords']),
                'status' => 'translated',
                'error' => null,
            ]);
        } catch (Throwable $e) {
            $this->rssImport->update([
                'status' => 'failed',
                'error' => mb_substr($e->getMessage(), 0, 2000),
            ]);

            Log::error('AI translation failed for rss_import #'.$this->rssImport->id, [
                'message' => $e->getMessage(),
            ]);
        }
    }
}