@extends('public.layout')

@section('title', localize($message, 'title'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <article class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($message->image)
                <img src="{{ Storage::url($message->image) }}" class="w-full h-64 md:h-96 object-cover" alt="{{ localize($message, 'title') }}">
            @endif
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm bg-primary/10 text-primary px-3 py-1 rounded-full font-medium">
                        @if($message->type === 'speech') {{ __('messages.speech') }}
                        @elseif($message->type === 'message') {{ __('messages.message') }}
                        @elseif($message->type === 'decree') {{ __('messages.decree') }}
                        @endif
                    </span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-relaxed">{{ localize($message, 'title') }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-8 pb-6 border-b">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $message->published_at?->format('Y/m/d H:i') ?? $message->created_at->format('Y/m/d H:i') }}</span>
                </div>
                @if($message->summary)
                    <div class="bg-primary/5 border-r-4 border-primary rounded-lg p-5 mb-8 text-gray-800 font-medium leading-relaxed">
                        {{ localize($message, 'summary') }}
                    </div>
                @endif
                <div class="article-body">
                    {!! localize($message, 'body') !!}
                </div>
            </div>
        </article>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                {{ __('messages.related_messages') }}
            </h3>
            <div class="space-y-4">
                @forelse($related as $item)
                    <a href="{{ route('public.messages.show', $item) }}" class="flex gap-3 group">
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
                    <p class="text-gray-500 text-sm">{{ __('messages.no_messages_available') }}</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('public.messages') }}" class="block bg-primary/5 text-primary text-center py-3 rounded-xl font-medium hover:bg-primary/10 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4 rotate-180 rtl:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('messages.back_to_messages') }}
        </a>
    </div>
</div>
@endsection