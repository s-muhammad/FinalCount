@extends('admin.layout')

@section('title', 'پیام‌ها')
@section('header', 'مدیریت پیام‌ها')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-bold text-gray-800">لیست پیام‌ها</h3>
        <a href="{{ route('admin.messages.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-light transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            پیام جدید
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عنوان</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($messages as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->title }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                {{ $item->type === 'speech' ? 'سخنرانی' : ($item->type === 'message' ? 'پیام' : 'حکم') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $item->status === 'published' ? 'bg-green-100 text-green-700' : ($item->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $item->status === 'published' ? 'منتشر شده' : ($item->status === 'draft' ? 'پیش‌نویس' : 'بایگانی') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ persian_date($item->created_at) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.messages.edit', $item) }}" class="p-1.5 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.messages.destroy', $item) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 hover:bg-red-50 rounded-lg">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">پیامی ثبت نشده</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $messages->links() }}
    </div>
</div>
@endsection
