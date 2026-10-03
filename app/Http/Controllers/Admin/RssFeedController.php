<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Interview;
use App\Models\Message;
use App\Models\News;
use App\Models\Quote;
use App\Models\RssFeed;
use App\Models\RssImport;
use App\Services\ArticleBodyExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RssFeedController extends Controller
{
    public function index(): View
    {
        $feeds = RssFeed::withCount('imports')->paginate(10);

        return view('admin.rss.index', compact('feeds'));
    }

    public function create(): View
    {
        return view('admin.rss.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateFeed($request);

        RssFeed::create($data);

        return redirect()->route('admin.rss.index')->with('success', 'منبع RSS با موفقیت اضافه شد.');
    }

    public function edit(RssFeed $rss): View
    {
        return view('admin.rss.edit', compact('rss'));
    }

    public function update(Request $request, RssFeed $rss): RedirectResponse
    {
        $data = $this->validateFeed($request);

        $rss->update($data);

        return redirect()->route('admin.rss.index')->with('success', 'منبع RSS به‌روزرسانی شد.');
    }

    public function destroy(RssFeed $rss): RedirectResponse
    {
        $rss->delete();

        return redirect()->route('admin.rss.index')->with('success', 'منبع RSS حذف شد.');
    }

    public function logs(Request $request): View
    {
        $status = $request->query('status');

        $imports = RssImport::with(['feed', 'news']);

        if ($status === 'published') {
            $imports->whereNotNull('published_as');
        } elseif (in_array($status, ['pending', 'translated', 'failed', 'ignored'])) {
            $imports->where('status', $status);
        }

        $imports = $imports->latest()->paginate(20)->withQueryString();

        $counts = RssImport::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $publishedCount = RssImport::whereNotNull('published_as')->count();

        return view('admin.rss.logs', compact('imports', 'counts', 'status', 'publishedCount'));
    }

    public function runNow(): RedirectResponse
    {
        try {
            $exit = \Illuminate\Support\Facades\Artisan::call('news:import-rss');
            $output = \Illuminate\Support\Facades\Artisan::output();

            $message = $exit === 0
                ? 'ایمپورت انجام شد. '.$output
                : 'ایمپورت با خطا مواجه شد.';

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function toggleStatus(RssImport $rssImport): RedirectResponse
    {
        $news = $rssImport->news;

        if (! $news) {
            return redirect()->back()->withErrors(['برای این مورد خبری وجود ندارد.']);
        }

        $news->status = $news->status === 'published' ? 'draft' : 'published';

        if ($news->status === 'published' && ! $news->published_at) {
            $news->published_at = now();
        }

        $news->save();

        $message = $news->status === 'published'
            ? 'خبر منتشر شد.'
            : 'خبر به پیش‌نویس برگشت.';

        return redirect()->back()->with('success', $message);
    }

    public function reject(RssImport $rssImport): RedirectResponse
    {
        if ($rssImport->news) {
            $rssImport->news->delete();
        }

        if ($rssImport->image) {
            Storage::disk('public')->delete($rssImport->image);
        }

        $rssImport->update([
            'status' => 'ignored',
            'published_as' => null,
            'news_id' => null,
            'error' => null,
        ]);

        return redirect()->back()->with('success', 'حذف شد؛ ایمپورت‌های بعدی آن را دوباره نمی‌آورند.');
    }

    public function convert(Request $request, RssImport $rssImport): RedirectResponse
    {
        $type = $request->input('type');

        if (! in_array($type, ['news', 'message', 'article', 'interview', 'quote', 'gallery'])) {
            return redirect()->back()->withErrors(['نوع مقصد نامعتبر است.']);
        }

        if ($rssImport->status === 'ignored') {
            return redirect()->back()->withErrors(['این مورد از گردونه حذف شده است.']);
        }

        if ($rssImport->published_as) {
            return redirect()->back()->withErrors(['این مورد قبلاً منتشر شده و قابل انتشار مجدد نیست.']);
        }

        $image = $this->copyImage($rssImport);

        // Copy whatever is already available on the import row (raw text, or
        // translated fields from previously translated rows). Items are created
        // as DRAFTS — translation + SEO is done afterwards on the edit page.
        $title = $rssImport->title ?: $rssImport->raw_title;
        $body = $rssImport->body ?: $rssImport->raw_body;

        // When the stored body is only a short snippet (no content:encoded in
        // the feed), fetch the full article text from the source page now so
        // the created item is complete before translating/publishing.
        if (mb_strlen($body) < 600 && $rssImport->source_url) {
            $fetched = app(ArticleBodyExtractor::class)->extract($rssImport->source_url);

            if ($fetched !== null && mb_strlen($fetched) > mb_strlen($body) && mb_strlen($fetched) >= 300) {
                $body = mb_substr($fetched, 0, 12000);
            }
        }

        $summary = $rssImport->summary ?: ($body ? Str::limit($body, 250) : null);

        $translated = [
            'title' => $title,
            'title_ar' => $rssImport->title_ar,
            'title_en' => $rssImport->title_en,
            'summary' => $summary,
            'summary_ar' => $rssImport->summary_ar,
            'summary_en' => $rssImport->summary_en,
            'body' => $body,
            'body_ar' => $rssImport->body_ar,
            'body_en' => $rssImport->body_en,
        ];

        $now = now();

        $labels = [
            'news' => 'اخبار',
            'message' => 'پیام',
            'article' => 'مقاله',
            'interview' => 'گفت‌وگو',
            'quote' => 'نقل‌قول',
            'gallery' => 'گالری',
        ];

        try {
            $created = match ($type) {
                'news' => News::create($translated + [
                    'image' => $image,
                    'slug' => $this->uniqueSlug('news', $title),
                    'source_url' => $rssImport->source_url,
                    'source_url_hash' => $rssImport->source_url_hash,
                    'status' => 'draft',
                ]),
                'message' => Message::create($translated + [
                    'image' => $image,
                    'slug' => $this->uniqueSlug('messages', $title),
                    'type' => 'message',
                    'status' => 'draft',
                ]),
                'article' => Article::create($translated + [
                    'image' => $image,
                    'slug' => $this->uniqueSlug('articles', $title),
                    'status' => 'draft',
                ]),
                'interview' => Interview::create($translated + [
                    'image' => $image,
                    'slug' => $this->uniqueSlug('interviews', $title),
                    'status' => 'draft',
                ]),
                'quote' => Quote::create([
                    'body' => $rssImport->body ?: ($rssImport->summary ?: $rssImport->raw_title),
                    'source' => $rssImport->feed?->name,
                    'date' => $now->toDateString(),
                    'image' => $image,
                ]),
                'gallery' => Gallery::create([
                    'title' => $rssImport->title ?: $rssImport->raw_title,
                    'image' => $image,
                    'description' => $rssImport->summary ?: $rssImport->raw_body,
                    'status' => 'draft',
                ]),
            };
        } catch (\Throwable $e) {
            if ($image) {
                Storage::disk('public')->delete($image);
            }

            return redirect()->back()->withErrors(['ثبت ناموفق بود: '.$e->getMessage()]);
        }

        $rssImport->update([
            'published_as' => $type,
            'news_id' => $type === 'news' ? $created->id : $rssImport->news_id,
        ]);

        $label = in_array($type, ['quote']) ? 'نقل‌قول' : ($labels[$type] ?? $type);
        $published = $type === 'quote' ? 'ایجاد شد' : 'به‌صورت پیش‌نویس ثبت شد';

        return redirect()->back()->with('success', 'با موفقیت در «'.$label.'» '.$published.'؛ برای ترجمه و انتشار آن را ویرایش کنید.');
    }

    private function copyImage(RssImport $rssImport): ?string
    {
        if (! $rssImport->image || ! Storage::disk('public')->exists($rssImport->image)) {
            return null;
        }

        $ext = pathinfo($rssImport->image, PATHINFO_EXTENSION) ?: 'jpg';
        $path = 'converted/'.Str::uuid().'.'.$ext;
        Storage::disk('public')->copy($rssImport->image, $path);

        return $path;
    }

    private function uniqueSlug(string $table, string $title): string
    {
        $slug = Str::slug($title) ?: 'item-'.Str::lower(Str::random(6));
        $base = $slug;
        $i = 2;

        while (DB::table($table)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    private function validateFeed(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'language' => ['required', 'in:fa,ar,en'],
            'is_active' => ['sometimes', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}