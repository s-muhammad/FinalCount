<?php

namespace App\Jobs;

use App\Models\News;
use App\Models\RssImport;
use App\Services\AiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiTranslateNewsJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;

    public $tries = 1;

    public function __construct(public News $news) {}

    public function handle(AiService $ai): void
    {
        $import = RssImport::where('news_id', $this->news->id)->first();

        if ($this->news->status !== 'draft') {
            if ($import && $import->status !== 'translated') {
                $import->update(['status' => 'skipped', 'error' => 'News is not a draft anymore; skipped.']);
            }

            return;
        }

        try {
            $result = $ai->translateAndSeo(
                $this->news->title,
                $this->news->summary ?? '',
                $this->news->body ?? '',
            );

            $this->news->update([
                'title' => $result['fa']['title'] ?: $this->news->title,
                'summary' => $result['fa']['summary'] ?: $this->news->summary,
                'body' => $result['fa']['body'] ?: $this->news->body,
                'title_ar' => $result['ar']['title'] ?: $this->news->title_ar,
                'summary_ar' => $result['ar']['summary'] ?: $this->news->summary_ar,
                'body_ar' => $result['ar']['body'] ?: $this->news->body_ar,
                'title_en' => $result['en']['title'] ?: $this->news->title_en,
                'summary_en' => $result['en']['summary'] ?: $this->news->summary_en,
                'body_en' => $result['en']['body'] ?: $this->news->body_en,
            ]);

            if ($import) {
                $import->update([
                    'status' => 'translated',
                    'keywords' => implode(', ', $result['keywords']),
                ]);
            }
        } catch (Throwable $e) {
            if ($import) {
                $import->update([
                    'status' => 'failed',
                    'error' => mb_substr($e->getMessage(), 0, 2000),
                ]);
            }

            Log::error('AI translation failed for news #'.$this->news->id, [
                'message' => $e->getMessage(),
            ]);
        }
    }
}