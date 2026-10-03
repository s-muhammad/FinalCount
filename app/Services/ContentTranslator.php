<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContentTranslator
{
    public function __construct(
        private AiService $ai,
        private ArticleBodyExtractor $extractor,
    ) {}

    /**
     * Translate a content model (News/Article/Message) that carries the
     * standard translatable fields (title/summary/body + _ar/_en).
     *
     * - mode "translate": keeps the fa content untouched, only producing ar/en.
     * - mode "seo": rewrites all three languages for SEO (fa included).
     * - when $sourceUrl is given and the stored body looks like a short
     *   snippet, the full article text is fetched from the source page first.
     */
    public function translate(Model $model, string $mode = 'seo', ?string $sourceUrl = null): bool
    {
        $translateOnly = $mode === 'translate';

        $rawTitle = (string) ($model->title ?? '');
        $rawBody = (string) ($model->body ?? '');

        if ($sourceUrl && mb_strlen($rawBody) < 600) {
            $fetched = $this->extractor->extract($sourceUrl);

            if ($fetched !== null && mb_strlen($fetched) > mb_strlen($rawBody) && mb_strlen($fetched) >= 300) {
                $rawBody = mb_substr($fetched, 0, 12000);
            }
        }

        $result = $translateOnly
            ? $this->ai->translateOnly($rawTitle, Str::limit($rawBody, 250), $rawBody)
            : $this->ai->translateAndSeo($rawTitle, Str::limit($rawBody, 250), $rawBody);

        $data = ['body' => $rawBody];

        if (! $translateOnly) {
            $data['title'] = $result['fa']['title'] ?: $rawTitle;
            $data['summary'] = $result['fa']['summary'] ?: Str::limit($rawBody, 250);
        }

        $data = $data + [
            'title_ar' => $result['ar']['title'] ?: $rawTitle,
            'summary_ar' => $result['ar']['summary'] ?: Str::limit($rawBody, 250),
            'body_ar' => $result['ar']['body'] ?: $rawBody,
            'title_en' => $result['en']['title'] ?: $rawTitle,
            'summary_en' => $result['en']['summary'] ?: Str::limit($rawBody, 250),
            'body_en' => $result['en']['body'] ?: $rawBody,
        ];

        $model->update($data);

        return true;
    }
}