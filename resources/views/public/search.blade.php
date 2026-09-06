@extends('public.layout')

@section('title', __('messages.search_placeholder'))

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.search_placeholder') }}</h1>
    @if($q)
        <p class="text-gray-600">{{ $results->count() }} نتیجه برای «{{ $q }}»</p>
    @endif
</div>

<div class="mb-6">
    <form action="{{ route('public.search') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('messages.search_placeholder') }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg text-sm hover:bg-primary-light transition">جستجو</button>
    </form>
</div>

@if($q && $results->count())
    <div class="space-y-4">
        @foreach($results as $result)
            @php
                $type = $result['type'];
                $item = $result['item'];
            @endphp
            <a href="@if($type === 'news') {{ route('public.news.show', $item) }} @elseif($type === 'message') {{ route('public.messages.show', $item) }} @elseif($type === 'media') {{ route('public.media.show', $item) }} @elseif($type === 'article') {{ route('public.culture') }} @endif" class="block bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    @if($item->image ?? $item->thumbnail)
                        <img src="{{ Storage::url($item->image ?? $item->thumbnail) }}" class="w-20 h-16 object-cover rounded-lg flex-shrink-0">
                    @else
                        <div class="w-20 h-16 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                            @if($type === 'news')
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            @elseif($type === 'message')
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            @elseif($type === 'media')
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($type === 'news') bg-blue-100 text-blue-700
                                @elseif($type === 'message') bg-green-100 text-green-700
                                @elseif($type === 'media') bg-purple-100 text-purple-700
                                @else bg-orange-100 text-orange-700
                                @endif">
                                @if($type === 'news') {{ __('messages.news') }}
                                @elseif($type === 'message') {{ __('messages.messages') }}
                                @elseif($type === 'media') {{ __('messages.media') }}
                                @else {{ __('messages.culture') }}
                                @endif
                            </span>
                            <span class="text-xs text-gray-400">{{ localize($item, 'title') ? Str::limit(localize($item, 'title'), 60) : '' }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 line-clamp-1">{{ localize($item, 'title') }}</h3>
                        @if(localize($item, 'summary'))
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">{{ localize($item, 'summary') }}</p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@elseif($q)
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <p class="text-gray-500">نتیجه‌ای یافت نشد</p>
    </div>
@endif
@endsection
