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
You are a professional news translator and SEO specialist.

Translate the following news article into Persian (fa), English (en) and Arabic (ar).

Rules:
- If the original text is already in one of these languages, keep that language version as-is (do not re-translate).
- Title: crisp, SEO-friendly, under 80 characters.
- Summary: keep it SHORT — 2-3 sentences that act as the meta description. Never duplicate the body.
- Body: write a COMPLETE, expanded news article of 250-450 words (about 3-5 paragraphs). Expand and deepen the provided information into publish-ready full text. It must be much longer and more detailed than the summary. If the source body is short or empty, write the article based on the title and summary.
- Structure the body for a news page — this is REQUIRED, never produce a plain unbroken text: divide the body into 2-4 sections. The body string must use these exact line-based markers:
  * A section heading alone on its own line, starting with exactly "## " (for H2). Example line: "## تحلیل محتوا"
  * A subheading alone on a line starting with "### "
  * Paragraphs separated from each other (and from headings) by a blank line
  * Bullet lists with lines starting with "- "
  * A quotable sentence alone on a line starting with "> "
- The body must contain at least one "## " heading, preceded by its paragraph(s).
- Do NOT include any HTML tags and no other markdown symbols.
- Also provide 5-8 SEO keywords (relevant to the article).

Here is the exact required BODY format (a real example of an "en" body value — follow this pattern in every language):

## Key Findings

The agreement was signed after months of negotiations between the parties.

## International Reaction

- Iranian officials welcomed the outcome
- Regional partners expressed their support

> Analysts call the move a turning point.

## Next Steps

Implementation is expected to begin early next year. Further talks will address the remaining details.

Respond with ONLY valid JSON:
{
  "fa": {"title": "...", "summary": "...", "body": "..."},
  "ar": {"title": "...", "summary": "...", "body": "..."},
  "en": {"title": "...", "summary": "...", "body": "..."},
  "keywords": ["keyword1", "keyword2"]
}

TITLE:
{$title}

SUMMARY:
{$summary}

BODY:
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