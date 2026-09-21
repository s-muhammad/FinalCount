<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.site_name')) - {{ __('messages.site_name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/vazirmatn.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        /* استایل محتوای خبر و پیام */
        .article-body {
            font-family: 'Vazirmatn', sans-serif;
            font-size: 17px;
            line-height: 2.2;
            color: #374151;
            text-align: justify;
        }
        .article-body p {
            margin-bottom: 1.5rem;
        }
        .article-body h2,
        .article-body h3 {
            font-weight: 800;
            color: #1f2937;
        }
        .article-body h2 {
            font-size: 1.45rem;
            border-right: 4px solid #1a4d2e;
            padding-right: 0.9rem;
            padding-bottom: 0.35rem;
            border-bottom: 1px solid #e5e7eb;
            margin: 2.25rem 0 1.1rem;
        }
        .article-body h3 {
            font-size: 1.2rem;
            margin: 1.75rem 0 0.75rem;
        }
        .article-body h2:first-child,
        .article-body h3:first-child {
            margin-top: 0;
        }
        .article-body ul,
        .article-body ol {
            margin: 1rem 0 1.75rem;
            padding-right: 1.5rem;
        }
        .article-body ul { list-style: disc; }
        .article-body ol { list-style: decimal; }
        .article-body li {
            margin-bottom: 0.5rem;
            line-height: 2;
        }
        .article-body a {
            color: #1a4d2e;
            text-decoration: underline;
        }
        .article-body blockquote {
            border-right: 4px solid #1a4d2e;
            background: #f0f7f2;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 1.75rem 0;
            font-style: italic;
            color: #4b5563;
        }
        .article-body img {
            border-radius: 12px;
            margin: 1.5rem auto;
            max-width: 100%;
        }
        .article-body strong { font-weight: 700; }
    </style>
</head>
<body class="bg-gray-100" x-data="{ mobileMenu: false }">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    @php $siteLogo = \App\Models\Setting::getValue('site_logo'); @endphp
                    <div class="w-10 h-12 ">
                        @if($siteLogo)
                            <img src="{{ Storage::url($siteLogo) }}" alt="{{ __('messages.site_name') }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-bold text-lg">ن</span>
                        @endif
                    </div>
                    <div class="text-right ">
                        <div class="text-primary font-bold text-sm">{{ __('messages.site_name') }}</div>
                        <div class="text-gray-500 text-xs">{{ __('messages.site_url') }}</div>
                    </div>
                </a>

                <!-- Desktop Menu -->
                @php
                    $activeModules = [];
                    foreach(['news', 'messages', 'media', 'articles', 'gallery', 'contact'] as $m) {
                        $activeModules[$m] = \App\Models\Setting::getValue("module_active_{$m}", '1') === '1';
                    }
                @endphp
                <nav class="hidden md:flex items-center gap-6 text-sm">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary transition">{{ __('messages.home') }}</a>
                    @if($activeModules['news'])
                        <a href="{{ route('public.news') }}" class="text-gray-700 hover:text-primary transition">{{ __('messages.news') }}</a>
                    @endif
                    @if($activeModules['messages'])
                        <a href="{{ route('public.messages') }}" class="text-gray-700 hover:text-primary transition">{{ __('messages.messages') }}</a>
                    @endif
                    @if($activeModules['media'])
                        <a href="{{ route('public.media') }}" class="text-gray-700 hover:text-primary transition">{{ __('messages.media') }}</a>
                    @endif
                    @if($activeModules['articles'])
                        <a href="{{ route('public.culture') }}" class="text-gray-700 hover:text-primary transition">{{ __('messages.culture') }}</a>
                    @endif
                    @if($activeModules['contact'])
                        <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-primary transition">{{ __('messages.contact') }}</a>
                    @endif
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <!-- Language Switcher -->
                    @php
                        $currentLocale = app()->getLocale();
                        $langFlags = ['fa' => '🇮🇷', 'ar' => '🇸🇦', 'en' => '🇬🇧'];
                        $langNames = ['fa' => 'فارسی', 'ar' => 'العربیة', 'en' => 'English'];
                    @endphp
                    <div x-data="{ openLang: false }" class="relative">
                        <button @click="openLang = !openLang" class="flex items-center gap-1 px-3 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 transition">
                            {{ $langFlags[$currentLocale] ?? '🌐' }} {{ $langNames[$currentLocale] ?? $currentLocale }} ▾
                        </button>
                        <div x-cloak x-show="openLang" @click.away="openLang = false" class="absolute left-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="/locale/fa" class="block px-4 py-2 text-sm hover:bg-gray-50 {{ $currentLocale === 'fa' ? 'bg-primary/10 text-primary' : '' }}">🇮🇷 فارسی</a>
                            <a href="/locale/ar" class="block px-4 py-2 text-sm hover:bg-gray-50 {{ $currentLocale === 'ar' ? 'bg-primary/10 text-primary' : '' }}">🇸🇦 العربیة</a>
                            <a href="/locale/en" class="block px-4 py-2 text-sm hover:bg-gray-50 {{ $currentLocale === 'en' ? 'bg-primary/10 text-primary' : '' }}">🇬🇧 English</a>
                        </div>
                    </div>

                    <!-- Search -->
                    <form action="{{ route('public.search') }}" method="GET" class="relative hidden sm:block">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}" class="border border-gray-300 rounded-lg px-4 py-2 pl-10 text-sm focus:outline-none focus:border-primary w-48">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </form>

                    <!-- Hamburger Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="p-2 hover:bg-gray-100 rounded-lg md:hidden">
                        <svg x-show="!mobileMenu" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenu" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden border-t border-gray-100 bg-white" style="display: none;">
            <div class="max-w-7xl mx-auto px-4 py-4 space-y-2">
                <!-- Mobile Search -->
                <form action="{{ route('public.search') }}" method="GET" class="relative mb-4 sm:hidden">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-10 text-sm focus:outline-none focus:border-primary">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
                <a href="{{ route('home') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('messages.home') }}</a>
                @if($activeModules['news'])
                    <a href="{{ route('public.news') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('messages.news') }}</a>
                @endif
                @if($activeModules['messages'])
                    <a href="{{ route('public.messages') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('messages.messages') }}</a>
                @endif
                @if($activeModules['media'])
                    <a href="{{ route('public.media') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('messages.media') }}</a>
                @endif
                @if($activeModules['articles'])
                    <a href="{{ route('public.culture') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('messages.culture') }}</a>
                @endif
                @if($activeModules['contact'])
                    <a href="{{ route('public.contact') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('messages.contact') }}</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        @php $footerLogo = \App\Models\Setting::getValue('site_logo'); @endphp
                        <div class="w-10 h-12">
                            @if($footerLogo)
                                <img src="{{ Storage::url($footerLogo) }}" alt="{{ __('messages.site_name') }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-bold text-lg">ن</span>
                            @endif
                        </div>
                        <span class="font-bold">{{ __('messages.site_name') }}</span>
                    </div>
                    <p class="text-gray-400 text-sm">{{ __('messages.footer_description') }}</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('messages.quick_access') }}</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        @if($activeModules['news'])
                            <li><a href="{{ route('public.news') }}" class="hover:text-white">{{ __('messages.news') }}</a></li>
                        @endif
                        @if($activeModules['messages'])
                            <li><a href="{{ route('public.messages') }}" class="hover:text-white">{{ __('messages.messages') }}</a></li>
                        @endif
                        @if($activeModules['media'])
                            <li><a href="{{ route('public.media') }}" class="hover:text-white">{{ __('messages.media') }}</a></li>
                        @endif
                        @if($activeModules['articles'])
                            <li><a href="{{ route('public.culture') }}" class="hover:text-white">{{ __('messages.culture') }}</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('messages.links') }}</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        @if($activeModules['contact'])
                            <li><a href="{{ route('public.contact') }}" class="hover:text-white">{{ __('messages.contact') }}</a></li>
                        @endif
                    </ul>
                </div>
                {{-- <div>
                    <h4 class="font-bold mb-4">{{ __('messages.contact_us') }}</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>{{ __('messages.address') }}</li>
                        <li>{{ __('messages.phone') }}</li>
                        <li>{{ __('messages.email') }}</li>
                    </ul>
                </div> --}}
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                {{ __('messages.copyright') }}
            </div>
        </div>
    </footer>
</body>
</html>
