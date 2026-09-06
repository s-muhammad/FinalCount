@extends('public.layout')

@section('title', localize($news, 'title'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <article class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($news->image)
                <img src="{{ Storage::url($news->image) }}" class="w-full h-64 md:h-96 object-cover" alt="{{ localize($news, 'title') }}">
            @endif
            <div class="p-6 md:p-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ localize($news, 'title') }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b">
                    <span>{{ $news->published_at?->format('Y/m/d H:i') ?? $news->created_at->format('Y/m/d H:i') }}</span>
                    @if($news->is_featured)
                        <span class="bg-primary/10 text-primary px-2 py-1 rounded-full">{{ __('messages.featured_news') }}</span>
                    @endif
                </div>
                @if($news->summary)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6 text-gray-700">
                        {{ localize($news, 'summary') }}
                    </div>
                @endif
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {{ localize($news, 'body') }}
                </div>
            </div>
        </article>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">{{ __('messages.related_news') }}</h3>
            <div class="space-y-4">
                @forelse($related as $item)
                    <a href="{{ route('public.news.show', $item) }}" class="flex gap-3 group">
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
                    <p class="text-gray-500 text-sm">{{ __('messages.no_news_available') }}</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('public.news') }}" class="block bg-primary/5 text-primary text-center py-3 rounded-xl font-medium hover:bg-primary/10 transition">
            {{ __('messages.back_to_news') }}
        </a>
    </div>
</div>
@endsection
