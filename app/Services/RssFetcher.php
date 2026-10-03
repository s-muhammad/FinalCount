<?php

namespace App\Services;

use App\Models\RssFeed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;

class RssFetcher
{
    public function fetch(RssFeed $feed): array
    {
        $response = Http::timeout(15)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; NewsBot/1.0)'])
            ->accept('application/rss+xml, application/xml, text/xml')
            ->get($feed->url);

        if ($response->failed()) {
            throw new \RuntimeException("RSS fetch failed: HTTP {$response->status()}");
        }

        $xml = @simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xml === false) {
            throw new \RuntimeException('RSS feed is not valid XML');
        }

        return $this->parse($xml);
    }

    private function parse(SimpleXMLElement $xml): array
    {
        $channel = $xml->channel ?? null;
        $items = $xml->xpath('//item');

        $results = [];

        foreach ($items as $item) {
            $title = trim((string) ($item->title ?? ''));
            $link = trim((string) ($item->link ?? ''));

            if ($title === '' || $link === '') {
                continue;
            }

            $results[] = [
                'title' => $title,
                'link' => $link,
                'description' => $this->cleanDescription((string) ($item->description ?? '')),
                'full_body' => $this->extractFullBody($item),
                'pub_date' => $this->parseDate((string) ($item->pubDate ?? '')),
                'image' => $this->extractImage($item),
            ];
        }

        return $results;
    }

    private function cleanDescription(string $description): string
    {
        $text = trim($description);

        if ($text === '') {
            return '';
        }

        if (mb_strpos($text, '<') !== false) {
            $text = strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return Str::limit(preg_replace('/\s+/u', ' ', $text), 500);
    }

    private function extractFullBody(SimpleXMLElement $item): string
    {
        $namespaces = $item->getNamespaces(true);

        if (! isset($namespaces['content'])) {
            return '';
        }

        $content = $item->children($namespaces['content']);
        $html = trim((string) ($content->encoded ?? ''));

        if ($html === '') {
            return '';
        }

        $text = $this->htmlToParagraphs($html);

        // Too short to be a real article — treat as absent so we fall back
        // to the description snippet (and later to fetching the article page).
        if (mb_strlen($text) < 300) {
            return '';
        }

        return $text;
    }

    public function htmlToParagraphs(string $html): string
    {
        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Turn block-level closers into paragraph breaks before stripping tags.
        $text = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $text);
        $text = preg_replace('/<\s*\/(p|div|li|h[1-6]|blockquote|tr|section|article)\s*>/i', "\n\n", $text);

        $text = strip_tags($text);

        $lines = preg_split('/\R/u', $text);
        $cleaned = [];

        foreach ($lines as $line) {
            $line = trim(preg_replace('/[ \t\x{00A0}]+/u', ' ', $line));

            if ($line !== '') {
                $cleaned[] = $line;
            }
        }

        $text = implode("\n\n", $cleaned);

        return trim(preg_replace('/\n{3,}/', "\n\n", $text));
    }

    private function parseDate(string $value): ?\DateTimeImmutable
    {
        if ($value === '') {
            return null;
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function extractImage(SimpleXMLElement $item): ?string
    {
        $namespaces = $item->getNamespaces(true);

        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                $url = trim((string) $media->content->attributes()->url);
                if ($url !== '') {
                    return $url;
                }
            }
            if (isset($media->thumbnail)) {
                $url = trim((string) $media->thumbnail->attributes()->url);
                if ($url !== '') {
                    return $url;
                }
            }
        }

        foreach ($item->enclosure as $enclosure) {
            $type = (string) $enclosure->attributes()->type;
            if (str_starts_with($type, 'image/')) {
                $url = trim((string) $enclosure->attributes()->url);
                if ($url !== '') {
                    return $url;
                }
            }
        }

        $description = (string) $item->description ?? '';
        $html = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
            return trim($m[1]);
        }

        return null;
    }
}