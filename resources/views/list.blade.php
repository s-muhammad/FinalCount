@extends('layout.app')
@section('main')

    <!-- هدر صفحه آرشیو -->
    <section class="relative pt-32 pb-20 px-4 border-b border-[var(--border-base)] overflow-hidden flex flex-col justify-center min-h-[40vh]">

        <!-- بررسی شرطی وجود تصویر -->
        @if(isset($pageImage) && $pageImage)
            <!-- تصویر پس‌زمینه -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset($pageImage) }}" class="w-full h-full object-cover opacity-40 mix-blend-overlay">
            </div>
            <!-- گرادیانت پوششی برای خوانایی متن -->
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-base)] via-[var(--bg-base-a80)] to-[var(--bg-base-a40)] z-0"></div>
        @else
            <!-- افکت‌های بک‌گراند حالت بدون عکس (ساده) -->
            <div class="absolute inset-0 bg-[var(--bg-base)] z-0"></div>
            <div class="absolute top-0 inset-x-0 h-64 bg-gradient-to-b from-[var(--accent-wash)] to-transparent z-0"></div>
            <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-yellow-600/10 blur-[120px] rounded-full z-0"></div>
        @endif

        <!-- محتوای متنی هدر -->
        <div class="relative z-10 max-w-4xl mx-auto text-center mt-6">
            {{--            <div class="inline-flex items-center gap-2 text-yellow-500/80 text-xs font-mono tracking-widest border border-yellow-600/30 rounded-full px-4 py-1.5 mb-6 backdrop-blur-sm bg-black/50 shadow-lg">--}}
            {{--                <i class="fas {{ $pageType == 'برچسب' ? 'fa-hashtag' : 'fa-folder-open' }}"></i>--}}
            {{--                <span>{{ $pageType }}</span>--}}
            {{--            </div>--}}

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[var(--text-primary)] mb-6 leading-tight drop-shadow-2xl">
                {{ $pageTitle }}
            </h1>

            @if($pageDescription)
                <p class="text-[var(--text-secondary)] text-sm md:text-base leading-relaxed max-w-2xl mx-auto drop-shadow-md">
                    {!! $pageDescription !!}
                </p>
            @endif
        </div>
    </section>

    <section class="py-16 px-4 max-w-7xl mx-auto min-h-[50vh]">

        @if($blogs->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center bg-[var(--bg-surface-a50)] rounded-3xl border border-dashed border-[var(--border-strong)]">
                <div class="w-20 h-20 bg-[var(--bg-input-strong)] rounded-full flex items-center justify-center mb-6 border border-[var(--border-strong)] text-[var(--text-faint)] text-3xl">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-bold text-[var(--text-secondary)] mb-2">{{ __('no_news_found') }}</h3>
                <p class="text-[var(--text-faint)] text-sm">{{ __('no_content_published') }}</p>
                <a href="/" class="mt-8 text-[var(--accent-deep)] hover:text-yellow-400 text-sm font-medium transition flex items-center gap-2">
                    <i class="fas {{ app()->getLocale() == 'en' ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                    {{ __('back_to_homepage') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php $currentLocale = app()->getLocale(); @endphp

                @foreach($blogs as $blog)
                    <div class="group bg-[var(--bg-surface)] border border-[var(--border-strong)] hover:border-yellow-600/50 rounded-2xl overflow-hidden transition-all duration-500 shadow-xl flex flex-col h-full relative">

                        <a href="{{ route('single', $blog->slug ?? $blog->id) }}" class="block h-52 overflow-hidden relative shrink-0">
                            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 opacity-80 group-hover:opacity-100">

                            <div class="absolute top-3 right-3">
                                {{--                                {{ $blog->category->getTranslation('name', $currentLocale) ?? 'عمومی' }}--}}
                                @php
                                    $localeTags = $blog->tags->where('type', $currentLocale);
                                    if($localeTags->isEmpty()) {
                                        $localeTags = $blog->tags->where('type', 'fa');
                                    }
                                @endphp
                                @foreach($localeTags->take(1) as $tag)
                                    <span class="bg-black/80 backdrop-blur-md border border-yellow-600/50 text-white text-[10px] font-bold px-3 py-1.5 rounded-full">
                                            #{{ $tag->getTranslation('name', $tag->type) }}
                                    </span>
                                @endforeach
                            </div>
                        </a>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center justify-between text-[var(--text-faint)] text-xs mb-4 font-mono">
                                <div class="flex items-center gap-1">
                                    <i class="far fa-calendar-alt text-yellow-600/70"></i>
                                    <span>{{ $blog->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="far fa-clock text-yellow-600/70"></i>
                                    <span>{{ __('reading_time') }} {{ $blog->reading_time }} {{ __('minutes') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('single', $blog->slug ?? $blog->id) }}">
                                <h3 class="text-lg md:text-xl font-bold mb-3 text-[var(--text-primary)] group-hover:text-yellow-400 transition leading-snug line-clamp-2">
                                    {{ $blog->title }}
                                </h3>
                            </a>

                            <p class="text-[var(--text-muted)] text-sm leading-relaxed mb-6 line-clamp-3 flex-grow">
                                {{ $blog->summary }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-[var(--border-base)] flex items-center justify-between">

                                <a href="{{ route('single', $blog->slug ?? $blog->id) }}" class="text-[var(--accent-deep)] text-xs font-bold hover:text-yellow-400 inline-flex items-center transition-colors">
                                    {{ __('read_more') }}
                                    {{--                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 rtl:-scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">--}}
                                    {{--                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />--}}
                                    {{--                                    </svg>--}}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center" dir="ltr">
                {{ $blogs->links() }}
            </div>

            <style>
                nav[role="navigation"] p {
                    color: var(--text-muted);
                    font-size: 0.875rem;
                    margin-top: 1rem;
                    text-align: center;
                }
                nav[role="navigation"] .relative.z-0.inline-flex {
                    flex-direction: row-reverse;
                }
                .pagination-link, nav[role="navigation"] span[aria-current="page"] span {
                    background-color: #ca8a04 !important; /* yellow-600 */
                    border-color: #ca8a04 !important;
                    color: black !important;
                    font-weight: 900;
                }
                nav[role="navigation"] a, nav[role="navigation"] span {
                    background-color: var(--bg-surface);
                    border-color: var(--border-strong);
                    color: var(--text-muted);
                }
                nav[role="navigation"] a:hover {
                    background-color: var(--bg-input-strong);
                    color: #eab308; /* yellow-500 */
                }
            </style>
        @endif

    </section>

@endsection
