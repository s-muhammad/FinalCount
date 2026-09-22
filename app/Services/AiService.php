<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class AiService
{
    public function translateAndSeo(string $title, string $summary, string $body): array
    {
        $provider = strtolower(config('services.ai.provider'));

        return match ($provider) {
            'gemini' => $this->translateWithGemini($title, $summary, $body),
            'mistral', 'deepseek', 'qwen', 'gapgpt' => $this->translateWithOpenAiCompatible(
                config("services.{$provider}"),
                $title,
                $summary,
                $body,
            ),
            default => throw new \RuntimeException('Unsupported AI provider: '.$provider),
        };
    }

    private function translateWithGemini(string $title, string $summary, string $body): array
    {
        $config = config('services.gemini');

        if (blank($config['key'])) {
            throw new \RuntimeException('GEMINI_API_KEY is not configured. Set it in your .env file.');
        }

        for ($attempt = 1; ; $attempt++) {
            $response = Http::timeout(60)
                ->acceptJson()
                ->withQueryParameters(['key' => $config['key']])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/'.($config['model'] ?? 'gemini-1.5-flash').':generateContent', [
                    'contents' => [
                        ['parts' => [['text' => $this->buildPrompt($title, $summary, $body)]]],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'response_mime_type' => 'application/json',
                    ],
                ]);

            if (! $response->failed()) {
                break;
            }

            if ($this->shouldRetry($response->status()) && $attempt < 3) {
                $this->throttleDelay($response, $attempt);

                continue;
            }

            throw new \RuntimeException('Gemini API error (HTTP '.$response->status().'): '.mb_substr($response->body(), 0, 300));
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text', '');

        if ($text === '') {
            throw new \RuntimeException('Gemini returned an empty response');
        }

        return $this->normalize($this->decodeJson($text), $title, $summary, $body);
    }

    private function translateWithOpenAiCompatible(array $config, string $title, string $summary, string $body): array
    {
        if (blank($config['key'])) {
            throw new \RuntimeException('Neither AI_PROVIDER key is configured for "'.config('services.ai.provider').'". Set the matching *_API_KEY in your .env file.');
        }

        for ($attempt = 1; ; $attempt++) {
            $response = Http::timeout(60)
                ->acceptJson()
                ->withToken($config['key'])
                ->post(rtrim($config['base_url'], '/').'/chat/completions', [
                    'model' => $config['model'],
                    'temperature' => 0.4,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => 'You only respond with valid JSON, no markdown, no extra text.'],
                        ['role' => 'user', 'content' => $this->buildPrompt($title, $summary, $body)],
                    ],
                ]);

            if (! $response->failed()) {
                break;
            }

            if ($this->shouldRetry($response->status()) && $attempt < 3) {
                $this->throttleDelay($response, $attempt);

                continue;
            }

            throw new \RuntimeException('AI API error (HTTP '.$response->status().'): '.mb_substr($response->body(), 0, 300));
        }

        $text = data_get($response->json(), 'choices.0.message.content', '');

        if ($text === '') {
            throw new \RuntimeException('AI returned an empty response');
        }

        return $this->normalize($this->decodeJson($text), $title, $summary, $body);
    }

    private function shouldRetry(int $status): bool
    {
        return $status === 429 || $status === 500 || $status === 502 || $status === 503;
    }

    private function throttleDelay(\Illuminate\Http\Client\Response $response, int $attempt): void
    {
        $retryAfter = $response->header('Retry-After');
        $delay = $retryAfter && is_numeric($retryAfter) ? (int) $retryAfter : min(30 * $attempt, 60);

        sleep($delay);
    }

    private function buildPrompt(string $title, string $summary, string $body): string
    {
        return <<<PROMPT
You are the senior editor of a professional news agency. Treat the supplied raw news item like a tip from a reporter: rewrite it yourself into a complete, publish-ready article in Persian (fa), Arabic (ar) and English (en). Your writing must be indistinguishable from a news site editor's work — fuller, clearer and more meaningful than the raw text.

Rules:
- DO NOT just rephrase or shorten the raw snippet. Create a full, standalone article in EVERY language, writing like a news editor:
  * Opening lead: who, what, when, where and why (a strong first paragraph).
  * Context and background: why it matters, what led to it.
  * Statements and details: what officials/people said, facts and numbers.
  * Perspective and possible consequences.
  * A proper closing paragraph.
- Each article must be SUBSTANTIAL: 6-8 paragraphs, roughly 300-400 words per language. The body must be far longer than the summary. Never leave the reader with half an idea.
- Keep the meaning faithful to the source; only expand with plausible, editorial context. Do not invent verifiable facts beyond general framing, names already given, and common-sense context.
- Title: a compelling, SEO-friendly news headline, under 80 characters.
- Summary: 2-3 short sentences as meta description — a teaser, under 200 characters, never a duplicate of the body, no markdown.
- Structure every long body with line-based markers (REQUIRED):
  * A section heading alone on its own line starting with exactly "## " (H2) — use at least two of them.
  * A subheading alone on a line starting with "### "
  * Paragraphs separated from each other (and from headings) by a BLANK line
  * Bullet lists with lines starting with "- "
  * A quotable sentence alone on a line starting with "> "
- No HTML tags and no other markdown symbols.
- Provide 5-8 SEO keywords relevant to the article.

Here is the exact required BODY pattern (a real example of an "en" body — follow this in every language):

## Agreement Reached After Negotiations

The two sides signed a landmark agreement on Tuesday in the capital, ending months of stalled talks. The deal is expected to unlock significant economic cooperation between the parties.

> "This is a historic step for both nations," a senior official said.

## What The Accord Includes

- Tariff reductions on key exports over the coming three years
- A joint committee to oversee implementation
- New visas for business travelers

## Regional Reaction

Neighboring countries welcomed the deal, while analysts note the next phase will test commitment on both sides. Full implementation is expected to begin early next year.

Respond with ONLY valid JSON:
{
  "fa": {"title": "...", "summary": "...", "body": "..."},
  "ar": {"title": "...", "summary": "...", "body": "..."},
  "en": {"title": "...", "summary": "...", "body": "..."},
  "keywords": ["keyword1", "keyword2"]
}

RAW TITLE:
{$title}

RAW SUMMARY:
{$summary}

RAW BODY:
{$body}
PROMPT;
    }

    private function decodeJson(string $text): array
    {
        $text = trim($text);
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        $text = trim($text);

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('AI response was not valid JSON');
        }

        return $decoded;
    }

    private function normalize(array $data, string $fallbackTitle = '', string $fallbackSummary = '', string $fallbackBody = ''): array
    {
        $result = [];

        foreach (['fa', 'ar', 'en'] as $lang) {
            $result[$lang] = [
                'title' => (string) (data_get($data, "{$lang}.title") ?: $fallbackTitle),
                'summary' => (string) (data_get($data, "{$lang}.summary") ?: $fallbackSummary),
                'body' => (string) (data_get($data, "{$lang}.body") ?: $fallbackBody),
            ];
        }

        $result['keywords'] = collect(data_get($data, 'keywords', []))
            ->map(fn ($k) => (string) $k)
            ->filter()
            ->values()
            ->all();

        return $result;
    }
}