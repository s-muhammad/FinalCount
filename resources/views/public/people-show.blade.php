@extends('public.layout')

@section('title', $person->name)

@section('content')
@php
    $labels = [
        'news' => 'اخبار',
        'message' => 'پیام',
        'article' => 'مقاله',
    ];
@endphp

<div class="mb-8 bg-white rounded-2xl p-8 shadow-sm flex items-center gap-5">
    @if($person->image)
        <img src="{{ Storage::url($person->image) }}" alt="{{ $person->name }}"
             class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover border-4 {{ $person->is_martyr ? 'border-green-600' : 'border-primary/30' }} flex-shrink-0">
    @endif
    <div>
        @if($person->is_martyr)
            <span class="inline-block bg-green-600 text-white text-xs px-3 py-1 rounded-full mb-2">شهید</span>
        @else
            <span class="inline-block bg-primary/10 text-primary text-xs px-3 py-1 rounded-full mb-2">چهره</span>
        @endif
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $person->name }}</h1>
        @if($person->description)
            <p class="text-gray-600 text-sm mt-2 leading-relaxed max-w-2xl">{{ $person->description }}</p>
        @endif
    </div>
</div>

<div class="mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-4">مقالات و محتوای مرتبط</h2>
    @if($items->count())
        <div class="space-y-4">
            @foreach($items as $entry)
                @php
                    $item = $entry['item'];
                    $link = match ($entry['type']) {
                        'news' => route('public.news.show', $item),
                        'message' => route('public.messages.show', $item),
                        'article' => route('public.culture'),
                    };
                @endphp
                <a href="{{ $link }}" class="block bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" class="w-full sm:w-44 h-40 object-cover flex-shrink-0" alt="{{ localize($item, 'title') }}">
                        @endif
                        <div class="p-4 flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[11px] {{ $entry['type'] === 'news' ? 'bg-blue-50 text-blue-600' : ($entry['type'] === 'message' ? 'bg-primary/10 text-primary' : 'bg-purple-50 text-purple-600') }} px-2 py-1 rounded-full">{{ $labels[$entry['type']] }}</span>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-1 line-clamp-2">{{ localize($item, 'title') }}</h3>
                            @if(localize($item, 'summary'))
                                <p class="text-gray-500 text-sm line-clamp-2">{{ localize($item, 'summary') }}</p>
                            @endif
                            <div class="text-xs text-gray-400 mt-2">{{ $item->published_at?->diffForHumans() ?? $item->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-500">
            هنوز محتوایی برای این چهره منتشر نشده است.
        </div>
    @endif
</div>
@endsection