@extends('public.layout')

@section('title', __('messages.culture'))

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.culture') }}</h1>
    <p class="text-gray-600">{{ __('messages.culture_description') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        @if($articles->count())
            <div class="space-y-6">
                @foreach($articles as $item)
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" class="w-full sm:w-48 h-48 object-cover" alt="{{ localize($item, 'title') }}">
                            @endif
                            <div class="p-4 flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($item->category)
                                        <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">{{ $item->category }}</span>
                                    @endif
                                </div>
                                <h2 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ localize($item, 'title') }}</h2>
                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ localize($item, 'summary') }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $item->author }}</span>
                                    <span>{{ $item->published_at?->diffForHumans() ?? $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-gray-500">{{ __('messages.no_articles_available') }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">{{ __('messages.selected_quotes') }}</h3>
            <div class="space-y-4">
                @php
                    $quotes = \App\Models\Quote::where('is_active', true)->inRandomOrder()->take(3)->get();
                @endphp
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
    </div>
</div>
@endsection
