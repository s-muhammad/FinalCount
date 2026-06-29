@extends('layout.app')
@section('main')
    <header class="relative w-full h-[calc(100vh-4.5rem)] flex items-center justify-center overflow-hidden bg-[#0c0c0e]">
        <img
            src="{{asset(setting('site_image'))}}"
            alt="{{ __('horizon_of_power_and_hope') }}"
            class="absolute inset-0 w-full h-full object-cover opacity-40 z-0"
        >
        <div class="absolute inset-0 bg-black/10 z-10"></div>
        <div class="absolute inset-0 radial-gradient z-10"></div>
        <div class="relative z-20 text-center px-4 max-w-6xl mx-auto w-full h-full flex flex-col justify-between py-4 md:py-6">
            <div class="pt-2">
                <div class="inline-flex items-center gap-3 bg-black/50 backdrop-blur-sm border border-yellow-600/50 px-4 py-1.5 rounded-full">
                    <span class="text-gray-300 text-xs">{{ __('historic_speech') }}</span>
                    <span class="w-1 h-1 bg-yellow-500 rounded-full"></span>
                    <span class="text-yellow-400 text-xs font-mono tracking-widest" dir="{{ app()->getLocale() == 'en' ? 'ltr' : 'rtl' }}">{{ __('speech_date') }}</span>
                </div>
            </div>
            <div class="flex-1 flex flex-col justify-center items-center my-auto space-y-4 md:space-y-6">
                <div class="space-y-1 md:space-y-2">
                    <div class="text-2xl md:text-4xl lg:text-5xl font-black leading-tight">
                        <span class="text-white">{{ __('title_part_1') }}</span>
                        {{--                    <br class="hidden md:block">--}}
                        <span class="text-white">{{ __('title_part_2') }}</span>
                    </div>
                    <p class="text-gray-400 text-sm md:text-base lg:text-lg max-w-2xl mx-auto font-light tracking-wide px-2">
                        {{ __('promise_quote') }}
                    </p>
                    <div class="flex justify-center mt-1">
                        <div class="w-16 h-0.5 bg-gradient-to-l from-yellow-500 to-transparent"></div>
                        <div class="w-16 h-0.5 bg-gradient-to-r from-yellow-500 to-transparent"></div>
                    </div>
                </div>

                <div class="bg-[#0b0c0e]/10 strategic-timer rounded-2xl p-4 lg:p-6 inline-block mx-auto shadow-2xl w-full max-w-4xl">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <span class="text-yellow-500 text-[10px] md:text-xs font-mono tracking-[0.2em] border border-yellow-500/30 px-3 py-0.5">{{ __('time_remaining') }}</span>
                    </div>

                    <!-- تایمر -->
                    <div id="timer" class="flex flex-wrap items-center justify-center gap-1 md:gap-3" dir="ltr">
                        <!-- سال -->
                        <div class="flex flex-col items-center w-14 md:w-20 lg:w-24">
                            <div class="bg-black/50 border border-yellow-600/50 rounded-lg p-1 w-full">
                                <span id="years" class="text-2xl md:text-4xl lg:text-5xl font-mono font-bold text-yellow-400 block text-center">00</span>
                            </div>
                            <span class="text-[9px] md:text-xs mt-1 text-yellow-600 font-medium">{{ __('year') }}</span>
                        </div>
                        <span class="text-lg md:text-2xl text-yellow-700 self-start mt-1">:</span>

                        <!-- ماه -->
                        <div class="flex flex-col items-center w-14 md:w-20 lg:w-24">
                            <div class="bg-black/50 border border-yellow-600/50 rounded-lg p-1 w-full">
                                <span id="months" class="text-2xl md:text-4xl lg:text-5xl font-mono font-bold text-yellow-400 block text-center">00</span>
                            </div>
                            <span class="text-[9px] md:text-xs mt-1 text-yellow-600 font-medium">{{ __('month') }}</span>
                        </div>
                        <span class="text-lg md:text-2xl text-yellow-700 self-start mt-1">:</span>

                        <!-- روز -->
                        <div class="flex flex-col items-center w-14 md:w-20 lg:w-24">
                            <div class="bg-black/50 border border-yellow-600/50 rounded-lg p-1 w-full">
                                <span id="days" class="text-2xl md:text-4xl lg:text-5xl font-mono font-bold text-yellow-400 block text-center">00</span>
                            </div>
                            <span class="text-[9px] md:text-xs mt-1 text-yellow-600 font-medium">{{ __('day') }}</span>
                        </div>
                        <span class="text-lg md:text-2xl text-yellow-700 self-start mt-1">:</span>

                        <!-- ساعت -->
                        <div class="flex flex-col items-center w-14 md:w-20 lg:w-24">
                            <div class="bg-black/50 border border-yellow-600/50 rounded-lg p-1 w-full">
                                <span id="hours" class="text-2xl md:text-4xl lg:text-5xl font-mono font-bold text-yellow-400 block text-center">00</span>
                            </div>
                            <span class="text-[9px] md:text-xs mt-1 text-yellow-600 font-medium">{{ __('hour') }}</span>
                        </div>
                        <span class="text-lg md:text-2xl text-yellow-700 self-start mt-1">:</span>

                        <!-- دقیقه -->
                        <div class="flex flex-col items-center w-14 md:w-20 lg:w-24">
                            <div class="bg-black/50 border border-yellow-600/50 rounded-lg p-1 w-full">
                                <span id="minutes" class="text-2xl md:text-4xl lg:text-5xl font-mono font-bold text-yellow-400 block text-center">00</span>
                            </div>
                            <span class="text-[9px] md:text-xs mt-1 text-yellow-600 font-medium">{{ __('minute') }}</span>
                        </div>
                        <span class="text-lg md:text-2xl text-yellow-700 self-start mt-1">:</span>

                        <!-- ثانیه -->
                        <div class="flex flex-col items-center w-14 md:w-20 lg:w-24">
                            <div class="bg-black/50 border border-yellow-600/50 rounded-lg p-1 w-full">
                                <span id="seconds" class="text-2xl md:text-4xl lg:text-5xl font-mono font-bold text-yellow-400 block text-center">00</span>
                            </div>
                            <span class="text-[9px] md:text-xs mt-1 text-yellow-600 font-medium">{{ __('second') }}</span>
                        </div>
                    </div>

                    <!-- تاریخ هدف -->
                    <div class="mt-4 text-gray-400 font-mono text-[10px] md:text-xs border-t border-yellow-600/20 pt-2">
                        <span class="text-yellow-500">{{ __('target_date') }}</span>
                    </div>
                </div>
            </div>

            <!-- بخش پایینی: دکمه اسکرول -->
            <div class="pb-2">
                <a href="#news-section" class="inline-flex items-center gap-2 text-yellow-500/70 hover:text-yellow-400 transition border border-yellow-600/30 rounded-full px-3 py-1 backdrop-blur-sm">
                    <span class="text-[10px] tracking-widest">{{ __('news_and_analysis') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </a>
            </div>
        </div>
    </header>
    <section id="news-section" class="py-20 px-4 max-w-7xl mx-auto scroll-mt-16">

        <div class="text-center mb-16">
            <span class="text-[var(--accent-deep)] font-mono text-sm tracking-widest border border-yellow-600/30 px-4 py-1">{{ __('analysis_and_tracking') }}</span>
            <h2 class="text-4xl md:text-5xl font-black mt-6 text-[var(--text-primary)]">{{ __('on_the_path_to_fulfillment') }}</h2>
            <p class="text-[var(--text-muted)] max-w-2xl mx-auto mt-4">{{ __('latest_news_subtitle') }}</p>
        </div>

        <div class="space-y-20">
            @foreach($categories->where('is_on_homepage', true) as $category)
                <div class="category-row">

                    <div class="flex items-end justify-between border-b border-[var(--border-strong)] pb-4 mb-8">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-yellow-600 rounded-sm"></span>
                            <div>
                                <h3 class="text-2xl md:text-3xl font-bold text-[var(--text-primary)]">{{ $category->name }}</h3>
                            </div>
                        </div>

                        <a href="{{ route('category.list',$category->id) }}" class="group flex items-center gap-2 text-sm text-[var(--accent-deep)] hover:text-yellow-400 transition-colors">
                            <span class="hidden md:inline">{{ __('view_all') }}</span>
                            {{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"--}}
                            {{--                                 class="w-4 h-4 group-hover:{{ app()->getLocale() == 'en' ? 'translate-x-1' : '-translate-x-1' }} transition-transform">--}}
                            {{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />--}}
                            {{--                            </svg>--}}
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($category->blogs()->latest()->take(3)->get() as $post)
                            <div class="group bg-[var(--bg-surface)] border border-[var(--border-strong)] hover:border-yellow-600/50 rounded-2xl overflow-hidden
                        transition-all duration-500 shadow-xl flex flex-col h-full">
                                <div class="h-48 overflow-hidden relative shrink-0">
                                    <img src="{{ asset($post->image) }}" alt="{{ __('news_title_alt') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 opacity-70 group-hover:opacity-100">
                                    <div class="absolute top-3 right-3 flex flex-wrap gap-2">
                                        @php
                                            $currentLocale = app()->getLocale();
                                            $localeTags = $post->tags->where('type', $currentLocale);
                                            if($localeTags->isEmpty()) {
                                                $localeTags = $post->tags->where('type', 'fa');
                                            }
                                        @endphp

                                        @foreach($localeTags as $tag)
                                            <span class="bg-black/70 backdrop-blur-md border border-yellow-600/50 text-white text-[10px] font-bold px-3 py-1.5 rounded-full">
                                            {{ $tag->getTranslation('name', $tag->type) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex items-center text-yellow-500/80 text-xs mb-3 font-mono">
                                        <i class="far fa-clock ml-1"></i>
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>

                                    <h3 class="text-xl font-bold mb-3 text-[var(--text-primary)] group-hover:text-yellow-400 transition line-clamp-2">
                                        {{ $post->title }}
                                    </h3>

                                    <p class="text-[var(--text-muted)] text-sm leading-relaxed mb-4 line-clamp-3 flex-grow">
                                        {{ $post->summary }}
                                    </p>

                                    <div class="mt-auto pt-4 border-t border-[var(--border-base)]">
                                        <a href="{{ route('single',$post->id) }}" class="text-[var(--accent-deep)] text-sm font-medium hover:text-yellow-400 inline-flex items-center transition-colors">
                                            {{ __('read_more') }}
                                            {{--                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-1 rtl:-scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">--}}
                                            {{--                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />--}}
                                            {{--                                            </svg>--}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-24 text-center border-t border-[var(--border-strong)] pt-12">
            <blockquote class="text-2xl md:text-3xl text-[var(--text-secondary)] italic max-w-4xl mx-auto font-light leading-snug">
                {{ __('leader_quote') }}
                <footer class="text-yellow-500 text-lg mt-6 not-italic font-medium">{{ __('quote_author') }}</footer>
            </blockquote>
        </div>
    </section>
@endsection
