@extends('admin.layout')

@section('title', 'داشبورد')
@section('header', 'داشبورد')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('admin.news.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">اخبار</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['news'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.messages.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">پیام‌ها</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['messages'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.media.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">چندرسانه‌ای</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['media'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.articles.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">مقالات</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['articles'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.interviews.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">گفتگوها</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['interviews'] }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.gallery.index') }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">گالری</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['gallery'] }}</p>
            </div>
            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b">
            <h3 class="font-bold text-gray-800">آخرین اخبار</h3>
        </div>
        <div class="p-6">
            @forelse($recentNews as $item)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="text-sm text-gray-800">{{ $item->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $item->status === 'published' ? 'bg-green-100 text-green-700' : ($item->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ $item->status === 'published' ? 'منتشر شده' : ($item->status === 'draft' ? 'پیش‌نویس' : 'بایگانی') }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">خبری ثبت نشده</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b">
            <h3 class="font-bold text-gray-800">آخرین پیام‌ها</h3>
        </div>
        <div class="p-6">
            @forelse($recentMessages as $item)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="text-sm text-gray-800">{{ $item->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $item->status === 'published' ? 'bg-green-100 text-green-700' : ($item->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ $item->status === 'published' ? 'منتشر شده' : ($item->status === 'draft' ? 'پیش‌نویس' : 'بایگانی') }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">پیامی ثبت نشده</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
