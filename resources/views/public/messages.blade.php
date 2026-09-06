@extends('public.layout')

@section('title', __('messages.messages'))

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.messages_list') }}</h1>
    <p class="text-gray-600">{{ __('messages.messages_description') }}</p>
</div>

@if($messages->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($messages as $item)
            <a href="{{ route('public.messages.show', $item) }}" class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" class="w-full h-48 object-cover" alt="{{ localize($item, 'title') }}">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">
                            @if($item->type === 'speech') {{ __('messages.speech') }}
                            @elseif($item->type === 'message') {{ __('messages.message') }}
                            @elseif($item->type === 'decree') {{ __('messages.decree') }}
                            @endif
                        </span>
                    </div>
                    <h2 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ localize($item, 'title') }}</h2>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ localize($item, 'summary') }}</p>
                    <span class="text-xs text-gray-500">{{ $item->published_at?->diffForHumans() ?? $item->created_at->diffForHumans() }}</span>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $messages->links() }}
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        <p class="text-gray-500">{{ __('messages.no_messages_available') }}</p>
    </div>
@endif
@endsection
