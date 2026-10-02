@extends('public.layout')

@section('title', localize($article, 'title'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <article class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($article->image)
                <img src="{{ Storage::url($article->image) }}" class="w-full h-64 md:h-96 object-cover" alt="{{ localize($article, 'title') }}">
            @endif
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @if($article->category)
                        <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">{{ $article->category }}</span>
                    @endif
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $article->author }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-relaxed">{{ localize($article, 'title') }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-8 pb-6 border-b">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ persian_date($article->published_at ?? $article->created_at, true) }}</span>
                </div>
                @if(localize($article, 'summary'))
                    <div class="bg-primary/5 border-r-4 border-primary rounded-lg p-5 mb-8 text-gray-800 font-medium leading-relaxed">
                        {{ localize($article, 'summary') }}
                    </div>
                @endif
                <div class="article-body">
                    {!! article_html(localize($article, 'body')) !!}
                </div>
            </div>
        </article>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                {{ __('messages.related_articles') }}
            </h3>
            <div class="space-y-4">
                @forelse($related as $item)
                    <a href="{{ route('public.culture.show', $item) }}" class="flex gap-3 group">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" class="w-20 h-16 object-cover rounded-lg flex-shrink-0">
                        @else
                            <div class="w-20 h-16 bg-gray-200 rounded-lg flex-shrink-0"></div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-primary transition">{{ localize($item, 'title') }}</h4>
                            <span class="text-xs text-gray-500 mt-1 block">{{ $item->published_at?->diffForHumans() ?? $item->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 text-sm">{{ __('messages.no_articles_available') }}</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">{{ __('messages.selected_quotes') }}</h3>
            <div class="space-y-4">
                @forelse($quotes as $quote)
                    <div class="border-b pb-4 last:border-0 last:pb-0">
                        <p class="text-gray-700 text-sm italic mb-2">"{{ localize($quote, 'body') }}"</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ localize($quote, 'source') }}</span>
                            <span>{{ $quote->date }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">{{ __('messages.no_quotes_available') }}</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('public.culture') }}" class="block bg-primary/5 text-primary text-center py-3 rounded-xl font-medium hover:bg-primary/10 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4 rotate-180 rtl:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('messages.back_to_culture') }}
        </a>
    </div>
</div>
@endsection