<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Throwable;

class ArticleBodyExtractor
{
    /**
     * Fetch an article page and extract its main readable text.
     * Returns null when the page cannot be fetched or no usable text is found.
     */
    public function extract(string $url): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; NewsBot/1.0)'])
                ->accept('text/html,application/xhtml+xml')
                ->get($url);
        } catch (Throwable) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $contentType = (string) $response->header('Content-Type');

        if ($contentType !== '' && ! str_contains(strtolower($contentType), 'html')) {
            return null;
        }

        return $this->extractFromHtml($response->body());
    }

    public function extractFromHtml(string $html): ?string
    {
        if (trim($html) === '') {
            return null;
        }

        $previous = libxml_use_internal_errors(true);

        try {
            $dom = new DOMDocument('1.0', 'UTF-8');
            // Force UTF-8 handling for pages without a proper meta charset.
            $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_NOWARNING | LIBXML_NOERROR);
        } catch (Throwable) {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);

            return null;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($dom);

        $container = $this->findMainContainer($xpath);

        if (! $container) {
            return null;
        }

        // Drop scripts and widgets that would otherwise leak into the text.
        // NOTE: <aside>/<header>/<footer>/<nav> must NOT be removed up front —
        // libxml's HTML parser produces a tree where removing <aside> corrupts
        // the whole document on some pages.
        foreach (['//script', '//style', '//noscript', '//iframe', '//button'] as $query) {
            foreach (iterator_to_array($xpath->query($query, $container)) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        $paragraphs = [];

        foreach ($xpath->query('.//p | .//h2 | .//h3 | .//h4 | .//blockquote | .//li', $container) as $node) {
            $text = trim(preg_replace('/\s+/u', ' ', $node->textContent));

            if (mb_strlen($text) >= 30) {
                $paragraphs[] = $text;
            }
        }

        // Drop accidental duplicate paragraphs (e.g. teasers repeated on the page).
        $paragraphs = array_values(array_unique($paragraphs));

        // Fallback: whole container text when no discrete blocks matched.
        if (count($paragraphs) < 2) {
            $text = trim(preg_replace('/\s+/u', ' ', $container->textContent));

            if (mb_strlen($text) < 300) {
                return null;
            }

            return $text;
        }

        return implode("\n\n", $paragraphs);
    }

    private function findMainContainer(DOMXPath $xpath): ?DOMElement
    {
        // Prefer a real <article> element holding enough paragraph text. The
        // first one on the page is often only a teaser card, so pick the one
        // with the most content.
        $best = null;
        $bestScore = 0;

        foreach ($xpath->query('//article') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $score = $this->paragraphScore($xpath, $node);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $node;
            }
        }

        if ($best && $bestScore >= 300) {
            return $best;
        }

        // Otherwise pick main/role=main/div/section holding the most paragraph text.
        $best = null;
        $bestScore = 0;

        foreach ($xpath->query('//main | //*[@role="main"] | //div | //section') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $score = $this->paragraphScore($xpath, $node);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $node;
            }
        }

        if ($best && $bestScore >= 300) {
            return $best;
        }

        return null;
    }

    private function paragraphScore(DOMXPath $xpath, DOMElement $node): int
    {
        $score = 0;

        foreach ($xpath->query('.//p', $node) as $p) {
            $score += mb_strlen(trim($p->textContent));
        }

        return $score;
    }
}