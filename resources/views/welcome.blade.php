@extends('public.layout')

@section('title', __('messages.home'))

@section('content')
<!-- Hero Section -->
<section class="mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Feature: اخبار ویژه -->
        <div class="lg:col-span-2 relative rounded-2xl overflow-hidden h-80 bg-gray-800">
            @if($featuredNews)
                @if($featuredNews->image)
                    <img src="{{ Storage::url($featuredNews->image) }}" alt="{{ localize($featuredNews, 'title') }}" class="w-full h-full object-cover">
                @else
                    <img src="https://placehold.co/800x400/1a1a1a/white?text=speech" alt="" class="w-full h-full object-cover opacity-80">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 right-0 p-6 text-white">
                    <h1 class="text-2xl font-bold mb-2">{{ localize($featuredNews, 'title') }}</h1>
                    <p class="text-sm text-gray-300 mb-4 line-clamp-2">{{ localize($featuredNews, 'summary') }}</p>
                    <a href="{{ route('public.news.show', $featuredNews) }}" class="bg-white/20 hover:bg-white/30 backdrop-blur px-4 py-2 rounded-lg text-sm transition inline-block">
                        {{ __('messages.view_full_statement') }}
                    </a>
                </div>
                <div class="absolute top-4 right-4">
                    <div class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold">
                        {{ __('messages.featured_news') }}
                    </div>
                </div>
            @else
                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                    <p class="text-gray-400">{{ __('messages.no_news') }}</p>
                </div>
            @endif
        </div>

        <!-- Small Banner: آخرین بیانات رهبر -->
        <div class="lg:h-[320px]">
            @if($latestSpeech)
                <a href="{{ route('public.messages.show', $latestSpeech) }}" class="block relative rounded-2xl overflow-hidden h-full min-h-[200px] bg-primary">
                    @if($latestSpeech->image)
                        <img src="{{ Storage::url($latestSpeech->image) }}" alt="{{ localize($latestSpeech, 'title') }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>
                    {{-- <div class="absolute top-4 right-4">
                        <span class="bg-white/20 backdrop-blur px-3 py-1.5 rounded-full text-xs text-white font-bold">
                            {{ __('messages.speech') }} 🎤
                        </span>
                    </div> --}}
                    <div class="absolute bottom-0 right-0 p-5 text-white">
                        <h3 class="font-bold text-lg mb-1 leading-relaxed">{{ localize($latestSpeech, 'title') }}</h3>
                        <span class="text-xs text-gray-300">{{ $latestSpeech->published_at?->diffForHumans() ?? $latestSpeech->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @else
                <div class="block relative rounded-2xl overflow-hidden h-full min-h-[200px] bg-primary flex items-center justify-center">
                    <span class="text-white/60 text-sm">{{ __('messages.no_messages') }}</span>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Countdown Section -->
@php
    $countdownTargetDate = \App\Models\Setting::getValue('countdown_target_date', '2040-09-09');
    $countdownBgColor = \App\Models\Setting::getValue('countdown_bg_color', '#1a4d2e');
    $countdownBgImage = \App\Models\Setting::getValue('countdown_bg_image', '');
@endphp
<section class="mb-6" x-data="{
    countdown: { years: 0, months: 0, days: 0, hours: 0, minutes: 0, seconds: 0 },
    init() {
        const target = new Date('{{ $countdownTargetDate }}T00:00:00+03:30').getTime();
        const targetDate = new Date('{{ $countdownTargetDate }}');
        const update = () => {
            const now = Date.now();
            const diff = target - now;
            if (diff <= 0) return;
            const totalSeconds = Math.floor(diff / 1000);
            const totalMinutes = Math.floor(totalSeconds / 60);
            const totalHours = Math.floor(totalMinutes / 60);
            const nowDate = new Date();
            this.countdown.years = targetDate.getFullYear() - nowDate.getFullYear();
            this.countdown.months = targetDate.getMonth() - nowDate.getMonth();
            this.countdown.days = targetDate.getDate() - nowDate.getDate();
            if (this.countdown.days < 0) {
                this.countdown.months--;
                const prevMonth = new Date(nowDate.getFullYear(), nowDate.getMonth(), 0);
                this.countdown.days += prevMonth.getDate();
            }
            if (this.countdown.months < 0) {
                this.countdown.years--;
                this.countdown.months += 12;
            }
            this.countdown.hours = totalHours % 24;
            this.countdown.minutes = totalMinutes % 60;
            this.countdown.seconds = totalSeconds % 60;
        };
        update();
        setInterval(update, 1000);
    }
}" x-init="init()">
    <div class="rounded-2xl py-8 md:py-10 relative overflow-hidden bg-cover bg-center"
         @if($countdownBgImage) style="background-image: url('{{ Storage::url($countdownBgImage) }}');" @else style="background-color: {{ $countdownBgColor }};" @endif>
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <div class="inline-block px-5 py-2 border border-white/30 rounded-full text-white text-sm mb-6 backdrop-blur-sm bg-white/10">
                {{ __('messages.countdown_announcement') }}
            </div>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4">
                {{ __('messages.countdown_title') }}
            </h2>
            <p class="text-white/80 text-sm md:text-base mb-8 max-w-2xl mx-auto">
                {{ __('messages.countdown_description') }}
            </p>
            <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-6" style="direction: ltr;">
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 md:px-4 py-2 md:py-3">
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-white" x-text="String(countdown.years).padStart(2, '0')"></div>
                    <div class="text-[9px] md:text-[10px] text-white/50 mt-0.5">{{ __('messages.years') }}</div>
                </div>
                <div class="flex items-center text-white/40 text-lg md:text-xl font-bold">:</div>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 md:px-4 py-2 md:py-3">
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-white" x-text="String(countdown.months).padStart(2, '0')"></div>
                    <div class="text-[9px] md:text-[10px] text-white/50 mt-0.5">{{ __('messages.months') }}</div>
                </div>
                <div class="flex items-center text-white/40 text-lg md:text-xl font-bold">:</div>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 md:px-4 py-2 md:py-3">
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-white" x-text="String(countdown.days).padStart(2, '0')"></div>
                    <div class="text-[9px] md:text-[10px] text-white/50 mt-0.5">{{ __('messages.days') }}</div>
                </div>
                <div class="flex items-center text-white/40 text-lg md:text-xl font-bold">:</div>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 md:px-4 py-2 md:py-3">
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-white" x-text="String(countdown.hours).padStart(2, '0')"></div>
                    <div class="text-[9px] md:text-[10px] text-white/50 mt-0.5">{{ __('messages.hours') }}</div>
                </div>
                <div class="w-full sm:hidden"></div>
                <div class="flex items-center text-white/40 text-lg md:text-xl font-bold">:</div>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 md:px-4 py-2 md:py-3">
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-white" x-text="String(countdown.minutes).padStart(2, '0')"></div>
                    <div class="text-[9px] md:text-[10px] text-white/50 mt-0.5">{{ __('messages.minutes') }}</div>
                </div>
                <div class="flex items-center text-white/40 text-lg md:text-xl font-bold">:</div>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg px-3 md:px-4 py-2 md:py-3">
                    <div class="text-xl md:text-2xl lg:text-3xl font-bold text-white" x-text="String(countdown.seconds).padStart(2, '0')"></div>
                    <div class="text-[9px] md:text-[10px] text-white/50 mt-0.5">{{ __('messages.seconds') }}</div>
                </div>
            </div>
            <p class="text-white/60 text-xs md:text-sm">
                {{ __('messages.target_date') }}
            </p>
        </div>
    </div>
</section>

<!-- News & Messages -->
@if(($activeModules['news'] ?? true) || ($activeModules['messages'] ?? true))
<section class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Latest News -->
        @if($activeModules['news'] ?? true)
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-primary">{{ __('messages.latest_news') }}</h2>
                <a href="{{ route('public.news') }}" class="text-sm text-primary hover:underline">{{ __('messages.view_all') }}</a>
            </div>
            <div class="space-y-4">
                @forelse($latestNews as $item)
                    <a href="{{ route('public.news.show', $item) }}" class="block border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start gap-3">
                            <span class="text-xs text-gray-400 mt-1">{{ $item->published_at?->format('Y/m/d') ?? $item->created_at->format('Y/m/d') }}</span>
                            <p class="text-sm text-gray-700 hover:text-primary">{{ localize($item, 'title') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">{{ __('messages.no_news') }}</p>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Messages -->
        @if($activeModules['messages'] ?? true)
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-primary">{{ __('messages.messages_box') }}</h2>
                <a href="{{ route('public.messages') }}" class="text-sm text-primary hover:underline">{{ __('messages.view_all') }}</a>
            </div>
            <div class="space-y-4">
                @forelse($latestMessages as $item)
                    <a href="{{ route('public.messages.show', $item) }}" class="block border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start gap-3">
                            <span class="text-xs text-gray-400 mt-1">{{ $item->published_at?->format('Y/m/d') ?? $item->created_at->format('Y/m/d') }}</span>
                            <p class="text-sm text-gray-700 hover:text-primary">{{ localize($item, 'title') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">{{ __('messages.no_messages') }}</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</section>
@endif

<!-- People & Martyrs -->
@if($people->isNotEmpty())
<section class="mb-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-primary">چهره‌ها و شهدا</h2>
            </div>
        </div>
        <div class="flex gap-5 overflow-x-auto scrollbar-hide pb-2" style="-webkit-overflow-scrolling: touch;">
            @foreach($people as $person)
                <a href="{{ route('public.people.show', $person) }}" class="flex flex-col items-center flex-shrink-0 w-28 group">
                    <div class="relative">
                        <img src="{{ Storage::url($person->image) }}" alt="{{ $person->name }}"
                             class="w-24 h-24 rounded-full object-cover border-4 {{ $person->is_martyr ? 'border-green-600' : 'border-primary/30' }} group-hover:scale-105 transition duration-300">
                        @if($person->is_martyr)
                            <span class="absolute -bottom-1 right-1/2 translate-x-1/2 bg-green-600 text-white text-[9px] px-2 py-0.5 rounded-full whitespace-nowrap">شهید</span>
                        @endif
                    </div>
                    <span class="text-sm text-gray-700 group-hover:text-primary mt-3 text-center leading-snug">{{ $person->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Media Section -->
@if($activeModules['media'] ?? true)
<section class="mb-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-primary">{{ __('messages.featured_media') }}</h2>
            </div>
            <a href="{{ route('public.media') }}" class="text-sm text-primary hover:underline">{{ __('messages.view_all') }}</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($latestMedia as $item)
                <a href="{{ route('public.media.show', $item) }}" class="relative rounded-xl overflow-hidden group cursor-pointer aspect-[3/4]">
                    @if($item->thumbnail)
                        <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ localize($item, 'title') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-primary flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/50" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 right-0 p-4 text-white">
                        <h3 class="font-bold text-sm">{{ localize($item, 'title') }}</h3>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-sm text-center py-4 col-span-4">{{ __('messages.no_media') }}</p>
            @endforelse
        </div>
    </div>
</section>
@endif

<!-- Quote & Gallery Section -->
@if(($activeModules['quotes'] ?? true) || ($activeModules['gallery'] ?? true))
<section class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Quotes -->
        @if($activeModules['quotes'] ?? true)
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-primary">{{ __('messages.quotes') }}</h2>
            </div>
            <div class="space-y-4">
                @forelse($quotes as $quote)
                    <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <p class="text-gray-700 text-sm italic mb-2">"{{ localize($quote, 'body') }}"</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ localize($quote, 'source') }}</span>
                            <span>{{ $quote->date }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">{{ __('messages.no_quotes') }}</p>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Gallery -->
        @if($activeModules['gallery'] ?? true)
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-primary">{{ __('messages.gallery') }}</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($gallery as $item)
                    <div class="relative rounded-xl overflow-hidden group cursor-pointer aspect-video">
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4 col-span-4">{{ __('messages.no_gallery') }}</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</section>
@endif
@endsection
