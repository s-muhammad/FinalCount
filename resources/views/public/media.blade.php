@extends('public.layout')

@section('title', __('messages.media'))

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.media') }}</h1>
    <p class="text-gray-600">{{ __('messages.media_description') }}</p>
</div>

@if($media->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($media as $item)
            <a href="{{ route('public.media.show', $item) }}" class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                @if($item->thumbnail)
                    <img src="{{ Storage::url($item->thumbnail) }}" class="w-full h-48 object-cover" alt="{{ localize($item, 'title') }}">
                @elseif($item->type === 'video')
                    <div class="w-full h-48 bg-gray-900 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                @elseif($item->type === 'audio')
                    <div class="w-full h-48 bg-primary flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                    </div>
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            @if($item->type === 'video') {{ __('messages.video') }}
                            @elseif($item->type === 'audio') {{ __('messages.audio') }}
                            @elseif($item->type === 'image') {{ __('messages.image') }}
                            @elseif($item->type === 'document') {{ __('messages.document') }}
                            @endif
                        </span>
                        <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">{{ $item->category }}</span>
                    </div>
                    <h2 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ localize($item, 'title') }}</h2>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ localize($item, 'description') }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $media->links() }}
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        <p class="text-gray-500">{{ __('messages.no_media_available') }}</p>
    </div>
@endif
@endsection
