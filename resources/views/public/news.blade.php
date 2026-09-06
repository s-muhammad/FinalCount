@extends('public.layout')

@section('title', __('messages.news'))

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.news') }}</h1>
    <p class="text-gray-600">{{ __('messages.news_description') }}</p>
</div>

@if($news->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($news as $item)
            <a href="{{ route('public.news.show', $item) }}" class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" class="w-full h-48 object-cover" alt="{{ localize($item, 'title') }}">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                @endif
                <div class="p-4">
                    <h2 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ localize($item, 'title') }}</h2>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ localize($item, 'summary') }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $item->published_at?->diffForHumans() ?? $item->created_at->diffForHumans() }}</span>
                        @if($item->is_featured)
                            <span class="bg-primary/10 text-primary px-2 py-1 rounded-full">{{ __('messages.featured') }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $news->links() }}
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        <p class="text-gray-500">{{ __('messages.no_news_available') }}</p>
    </div>
@endif
@endsection
