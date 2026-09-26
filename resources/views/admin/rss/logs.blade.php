@extends('admin.layout')

@section('title', 'خبرخوان')
@section('header', 'خبرخوان RSS')

@section('content')
@php
    $badges = fn ($s) => match ($s) {
        'translated' => ['bg-green-100 text-green-700', 'ترجمه شد'],
        'pending' => ['bg-yellow-100 text-yellow-700', 'در انتظار'],
        'failed' => ['bg-red-100 text-red-700', 'خطا'],
        'ignored' => ['bg-gray-100 text-gray-500', 'رد شده'],
        default => ['bg-gray-100 text-gray-700', $s],
    };

    $destLabels = [
        'news' => 'اخبار',
        'message' => 'پیام',
        'article' => 'مقاله',
        'interview' => 'گفت‌وگو',
        'quote' => 'نقل‌قول',
        'gallery' => 'گالری',
    ];
@endphp

<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-bold text-gray-800">خبرخوان</h3>
        <form action="{{ route('admin.rss.run') }}" method="POST" onsubmit="this.querySelector('button').disabled = true">
            @csrf
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-light transition">
                اجرای ایمپورت
            </button>
        </form>
    </div>

    <div class="px-6 py-3 border-b flex flex-wrap items-center gap-2">
        <span class="text-xs text-gray-400">فیلتر:</span>
        @php
            $tabs = [
                null => ['همه', $imports->total()],
                'pending' => ['در انتظار', $counts['pending'] ?? 0],
                'translated' => ['ترجمه شد', $counts['translated'] ?? 0],
                'published' => ['منتشر شده', $publishedCount],
                'failed' => ['خطا', $counts['failed'] ?? 0],
                'ignored' => ['رد شده', $counts['ignored'] ?? 0],
            ];
        @endphp
        @foreach ($tabs as $value => [$label, $count])
            <a href="{{ route('admin.rss.logs', $value ? ['status' => $value] : []) }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium transition {{ $status === $value ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }} ({{ $count }})
            </a>
        @endforeach
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">عنوان اصلی</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($imports as $import)
                    @php [$badgeClass, $badgeLabel] = $badges($import->status); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div class="flex items-start gap-3">
                                @if($import->image)
                                    <img src="{{ Storage::url($import->image) }}" alt="" class="w-16 h-12 rounded-lg object-cover flex-shrink-0 border border-gray-100">
                                @else
                                    <div class="w-16 h-12 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center text-[10px] text-gray-400">بدون تصویر</div>
                                @endif
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-gray-800 max-w-xs truncate">{{ $import->raw_title ?: $import->title }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $import->feed?->name ?? '-' }}</div>
                                    @if($import->keywords)
                                        <div class="text-xs text-gray-400 mt-0.5 max-w-xs truncate" dir="ltr">{{ $import->keywords }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $badgeClass }}" title="{{ $import->status === 'failed' ? $import->error : '' }}">{{ $badgeLabel }}</span>
                            @if($import->published_as)
                                <div class="text-[10px] text-green-600 mt-1">✔ منتشر شد → {{ $destLabels[$import->published_as] ?? $import->published_as }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">{{ persian_date($import->created_at, true) }}</td>
                        <td class="px-4 py-4">
                            @if($import->status === 'ignored')
                                <span class="text-xs text-gray-400">—</span>
                            @elseif($import->published_as)
                                @if($import->news)
                                    <a href="{{ route('admin.news.edit', $import->news) }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs text-gray-600 hover:bg-gray-50">ویرایش خبر</a>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            @else
                                <div class="flex flex-wrap items-center gap-2">
                                    <form action="{{ route('admin.rss.convert', $import) }}" method="POST" class="flex items-center gap-1">
                                        @csrf
                                        <select name="type" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-primary">
                                            <option value="news">انتشار به اخبار</option>
                                            <option value="message">انتشار به پیام</option>
                                            <option value="article">انتشار به مقاله</option>
                                            <option value="interview">انتشار به گفت‌وگو</option>
                                            <option value="quote">انتشار به نقل‌قول</option>
                                            <option value="gallery">انتشار به گالری</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">انتشار</button>
                                    </form>

                                    <form action="{{ route('admin.rss.reject', $import) }}" method="POST" onsubmit="return confirm('این مورد حذف شود و دیگر ایمپورت نشود؟')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100">حذف از گردونه</button>
                                    </form>

                                    @if(!$import->title_en)
                                        <span class="text-xs text-gray-400">(ترجمه نشده — ابتدا «اجرای ایمپورت»)</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">ایمپورتی یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $imports->links() }}
    </div>
</div>
@endsection