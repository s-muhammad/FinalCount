@extends('admin.layout')

@section('title', 'گزارش ایمپورت‌ها')
@section('header', 'گزارش ایمپورت‌های RSS')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-bold text-gray-800">لاگ ایمپورت</h3>
        <a href="{{ route('admin.rss.index') }}" class="text-sm text-primary hover:underline">بازگشت به منابع</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عنوان اصلی</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">منبع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">خبر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($imports as $import)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-800 max-w-xs truncate">{{ $import->raw_title }}</div>
                            @if($import->keywords)
                                <div class="text-xs text-gray-400 mt-1 max-w-xs truncate" dir="ltr">{{ $import->keywords }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $import->feed?->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($import->status === 'translated')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">ترجمه شد</span>
                            @elseif($import->status === 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">در انتظار</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700" title="{{ $import->error }}">خطا</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($import->news)
                                <a href="{{ route('admin.news.edit', $import->news) }}" class="text-sm text-primary hover:underline">مشاهده</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ persian_date($import->created_at, true) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">هنوز ایمپورتی انجام نشده است.</td>
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