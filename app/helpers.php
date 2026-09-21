<?php

if (!function_exists('localize')) {
    function localize($model, $field)
    {
        if (!$model) return '';
        $locale = App::getLocale();
        if ($locale === 'fa') return $model->$field ?? '';
        $localized = $model->{$field . '_' . $locale};
        return $localized ?? $model->$field ?? '';
    }
}

if (!function_exists('article_html')) {
    /**
     * Convert plain article text (with ## headings, - bullets, > quotes)
     * into safe, styled HTML.
     */
    function article_html(?string $text): \Illuminate\Support\HtmlString
    {
        if ($text === null || trim($text) === '') {
            return new \Illuminate\Support\HtmlString('');
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $n = count($lines);
        $i = 0;
        $html = [];

        while ($i < $n) {
            $line = trim((string) $lines[$i]);

            if ($line === '') {
                $i++;
                continue;
            }

            if (preg_match('/^###\s+(.+)$/', $line, $m)) {
                $html[] = '<h3>'.e($m[1]).'</h3>';
                $i++;
                continue;
            }

            if (preg_match('/^##\s+(.+)$/', $line, $m)) {
                $html[] = '<h2>'.e($m[1]).'</h2>';
                $i++;
                continue;
            }

            if (preg_match('/^>\s?(.+)$/', $line, $m)) {
                $html[] = '<blockquote>'.e($m[1]).'</blockquote>';
                $i++;
                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $line, $m)) {
                $items = [];
                while ($i < $n && preg_match('/^[-*]\s+(.+)$/', trim((string) $lines[$i]), $im)) {
                    $items[] = '<li>'.e($im[1]).'</li>';
                    $i++;
                }
                $html[] = '<ul>'.implode('', $items).'</ul>';
                continue;
            }

            if (preg_match('/^\d+[.)]\s+(.+)$/', $line, $m)) {
                $items = [];
                while ($i < $n && preg_match('/^\d+[.)]\s+(.+)$/', trim((string) $lines[$i]), $im)) {
                    $items[] = '<li>'.e($im[1]).'</li>';
                    $i++;
                }
                $html[] = '<ol>'.implode('', $items).'</ol>';
                continue;
            }

            // plain paragraph until a blank line or another marker
            $paragraph = [];
            while ($i < $n) {
                $p = trim((string) $lines[$i]);
                if ($p === '' || preg_match('/^(#{1,3}\s|[-*]\s|\d+[.)]\s|>)/', $p)) {
                    break;
                }
                $paragraph[] = $p;
                $i++;
            }
            $html[] = '<p>'.e(implode(' ', $paragraph)).'</p>';
        }

        return new \Illuminate\Support\HtmlString(implode("\n", $html));
    }
}

if (!function_exists('persian_date')) {
    /**
     * Convert a date to the Persian (Shamsi) calendar.
     */
    function persian_date($date, bool $withTime = false): string
    {
        if (!$date) {
            return '';
        }

        try {
            $carbon = $date instanceof \Carbon\CarbonInterface
                ? $date
                : \Carbon\Carbon::parse($date);

            $jalali = \Morilog\Jalali\Jalalian::fromCarbon($carbon);

            return $withTime
                ? $jalali->format('Y/m/d H:i')
                : $jalali->format('Y/m/d');
        } catch (\Throwable $e) {
            return '';
        }
    }
}