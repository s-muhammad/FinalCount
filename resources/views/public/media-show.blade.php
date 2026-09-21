@extends('public.layout')

@section('title', localize($medium, 'title'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <article class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($medium->thumbnail)
                <img src="{{ Storage::url($medium->thumbnail) }}" class="w-full h-64 md:h-96 object-cover" alt="{{ localize($medium, 'title') }}">
            @elseif($medium->type === 'video')
                <div class="w-full h-64 md:h-96 bg-gray-900 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
            @elseif($medium->type === 'audio')
                <div class="w-full h-64 md:h-96 bg-primary flex items-center justify-center">
                    <svg class="w-24 h-24 text-white/80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                </div>
            @endif
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full">
                        @if($medium->type === 'video') {{ __('messages.video') }}
                        @elseif($medium->type === 'audio') {{ __('messages.audio') }}
                        @elseif($medium->type === 'image') {{ __('messages.image') }}
                        @elseif($medium->type === 'document') {{ __('messages.document') }}
                        @endif
                    </span>
                    <span class="text-sm bg-primary/10 text-primary px-3 py-1 rounded-full">{{ $medium->category }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ localize($medium, 'title') }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b">
                    <span>{{ persian_date($medium->created_at) }}</span>
                </div>
                @if($medium->description)
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {{ localize($medium, 'description') }}
                    </div>
                @endif
                @if($medium->file)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <a href="{{ Storage::url($medium->file) }}" target="_blank" class="inline-flex items-center gap-2 text-primary hover:underline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ __('messages.download_file') }}
                        </a>
                    </div>
                @endif
            </div>
        </article>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">{{ __('messages.media_info') }}</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.type') }}:</dt>
                    <dd class="text-gray-800">
                        @if($medium->type === 'video') {{ __('messages.video') }}
                        @elseif($medium->type === 'audio') {{ __('messages.audio') }}
                        @elseif($medium->type === 'image') {{ __('messages.image') }}
                        @elseif($medium->type === 'document') {{ __('messages.document') }}
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.category') }}:</dt>
                    <dd class="text-gray-800">{{ $medium->category }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ __('messages.date') }}:</dt>
                    <dd class="text-gray-800">{{ persian_date($medium->created_at) }}</dd>
                </div>
            </dl>
        </div>

        @include('public.partials.ad-banner')

        <a href="{{ route('public.media') }}" class="block bg-primary/5 text-primary text-center py-3 rounded-xl font-medium hover:bg-primary/10 transition">
            {{ __('messages.back_to_media') }}
        </a>
    </div>
</div>
@endsection
