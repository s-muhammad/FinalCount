<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RssFeed;
use App\Models\RssImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function logs(): View
    {
        $imports = RssImport::with(['feed', 'news'])
            ->latest()
            ->paginate(20);

        return view('admin.rss.logs', compact('imports'));
    }

    public function runNow(): RedirectResponse
    {
        try {
            $exit = \Illuminate\Support\Facades\Artisan::call('news:import-rss', ['--sync' => true, '--retry-failed' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();

            $message = $exit === 0
                ? 'ایمپورت انجام شد. '.$output
                : 'ایمپورت با خطا مواجه شد.';

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
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