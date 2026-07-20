<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('site-theme');
                var theme = (saved === 'dark' || saved === 'light') ? saved : 'light';
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
        function toggleSiteTheme() {
            var html = document.documentElement;
            var next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            try { localStorage.setItem('site-theme', next); } catch (e) {}
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_title') }}</title>
    <meta content="{{ setting('site_description') }}" name="description">
    <meta name="author" content="{{ setting('seo_meta_author') }}">
    <meta name="keywords" content="{{ setting('seo_meta_keywords') }}">
    <meta property="og:image" content="{{ setting('seo_og_image') }}">
    <link rel="icon" href="{{ setting('site_favicon') }}">

    @vite(['resources/js/app.js','resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { font-family: 'Vazirmatn', sans-serif; }

        :root {
            --bg-base: #f6f3ec;
            --bg-surface: #ffffff;
            --bg-surface-translucent: rgba(255, 255, 255, 0.72);
            --bg-surface-a50: rgba(255, 255, 255, 0.5);
            --bg-input: rgba(20, 18, 10, 0.04);
            --bg-input-strong: rgba(20, 18, 10, 0.07);
            --display-bg: rgba(10, 10, 12, 0.92);
            --hover-wash: rgba(20, 18, 10, 0.04);
            --img-fade: #ffffff;
            --bg-base-a80: rgba(246, 243, 236, 0.85);
            --bg-base-a40: rgba(246, 243, 236, 0.45);
            --accent-wash: rgba(234, 179, 8, 0.12);
            --text-primary: #1c1a14;
            --text-secondary: #4b4738;
            --text-muted: #6b6452;
            --text-faint: #938971;
            --border-base: rgba(20, 18, 10, 0.08);
            --border-strong: #e7e1d2;
            --border-input: rgba(20, 18, 10, 0.14);
            --accent-deep: #8a6107;
            --card-shadow: rgba(70, 55, 10, 0.10);
        }

        html[data-theme="dark"] {
            --bg-base: #09090b;
            --bg-surface: #111316;
            --bg-surface-translucent: rgba(20, 20, 20, 0.4);
            --bg-surface-a50: rgba(17, 19, 22, 0.5);
            --bg-input: rgba(0, 0, 0, 0.4);
            --bg-input-strong: rgba(0, 0, 0, 0.6);
            --display-bg: rgba(0, 0, 0, 0.6);
            --hover-wash: rgba(255, 255, 255, 0.05);
            --img-fade: #141414;
            --bg-base-a80: rgba(9, 9, 11, 0.85);
            --bg-base-a40: rgba(9, 9, 11, 0.45);
            --accent-wash: rgba(120, 53, 15, 0.25);
            --text-primary: #ffffff;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --text-faint: #6b7280;
            --border-base: rgba(255, 255, 255, 0.05);
            --border-strong: #1f2937;
            --border-input: rgba(255, 255, 255, 0.1);
            --accent-deep: #ca8a04;
            --card-shadow: rgba(0, 0, 0, 0.5);
        }

        body {
            transition: background-color .25s ease, color .25s ease;
        }

        .bg-cinematic {
            background: radial-gradient(circle at 50% 100%, rgba(30, 27, 20, 0.4) 0%, rgba(9, 9, 11, 1) 80%);
        }

        .glass-panel {
            background: var(--bg-surface-translucent);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-base);
            box-shadow: 0 10px 30px -10px var(--card-shadow);
            transition: background-color .25s ease, border-color .25s ease;
        }

        .article-content p {
            color: var(--text-secondary);
            line-height: 2.1;
            font-size: 1.125rem;
            margin-bottom: 1.75rem;
            text-align: justify;
        }

        /* ===== دکمه روز/شب ===== */
        .theme-toggle .icon-sun,
        .theme-toggle .icon-moon {
            display: none;
        }
        html[data-theme="light"] .theme-toggle .icon-moon { display: block; }
        html[data-theme="dark"] .theme-toggle .icon-sun { display: block; }
    </style>
</head>
<body class="bg-[var(--bg-base)] text-[var(--text-primary)] antialiased
{{--bg-cinematic min-h-screen flex flex-col justify-between overflow-x-hidden--}}
">

<nav x-data="{ mobileMenuOpen: false }" class="w-full border-b border-white/5 sticky top-0 z-50 bg-[#09090b]/80 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18">

            <div class="flex items-center gap-8 lg:gap-10">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition">
                        @if(setting('site_logo'))
                            <img src="{{asset(setting('site_logo'))}}" style="width: 45px" alt="">
                        @endif
{{--                        <span class="text-yellow-500 font-black text-sm">{{ __('logo_letter') }}</span>--}}
                    </div>
                    <span class="text-white font-black tracking-widest text-lg group-hover:text-yellow-400 transition">{{ __('site_title') }}</span>
                </a>
                <div class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium">
                    @foreach(\App\Models\Category::where('is_in_menu', true)->get() as  $menu)
                        <a href="{{ route('category.list',$menu->id) }}" class="text-white hover:text-yellow-400 transition">{{ $menu->name }}</a>
                    @endforeach
                </div>
            </div>

            <!-- دکمه‌های سمت چپ (تغییر زبان، تغییر حالت روز/شب و دکمه همبرگری موبایل) -->
            <div class="flex items-center gap-4">

                <!-- دکمه تغییر حالت روز و شب -->
                <button @click="toggleSiteTheme()" type="button"
                        class="theme-toggle flex items-center justify-center w-10 h-10 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-yellow-400 rounded-xl transition duration-300 focus:outline-none focus:border-yellow-500/50"
                        aria-label="تغییر حالت روز و شب" title="حالت روز / شب">
                    <svg class="icon-sun w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m9-9h-1.5m-15 0H3m15.36-6.36-1.06 1.06M6.7 17.3l-1.06 1.06m12.72 0-1.06-1.06M6.7 6.7 5.64 5.64M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z" />
                    </svg>
                    <svg class="icon-moon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                <!-- دکمه تغییر زبان -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                            class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 px-4 py-2 rounded-xl transition duration-300 focus:outline-none focus:border-yellow-500/50">
                        <span class="text-sm font-mono tracking-widest uppercase">
                            {{ app()->getLocale() }}
                        </span>
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- لیست زبان‌ها -->
                    <div x-show="dropdownOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         style="display: none;"
                         class="absolute left-0 mt-2 w-40 bg-[#111316]/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl py-2 z-50 overflow-hidden">

                        <a href="/lang/fa" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition w-full text-right {{ app()->getLocale() == 'fa' ? 'bg-white/5 text-yellow-400 font-bold border-r-2 border-yellow-500' : '' }}">
                            <span>🇮🇷</span> فارسی
                        </a>

                        <a href="/lang/ar" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition w-full text-right {{ app()->getLocale() == 'ar' ? 'bg-white/5 text-yellow-400 font-bold border-r-2 border-yellow-500' : '' }}">
                            <span>🇸🇦</span> العربية
                        </a>

                        <a href="/lang/en" dir="ltr" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition w-full text-left {{ app()->getLocale() == 'en' ? 'bg-white/5 text-yellow-400 font-bold border-l-2 border-yellow-500' : '' }}">
                            <span>🇬🇧</span> English
                        </a>
                    </div>
                </div>

                <!-- دکمه منوی همبرگری برای موبایل -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>
        </div>
    </div>

    <!-- کشوی منوی موبایل (کاملاً هماهنگ با گزینه‌های جدید) -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         style="display: none;"
         class="md:hidden absolute top-18 left-0 w-full z-50 border-b border-white/10 shadow-2xl bg-[#09090b]/98 backdrop-blur-xl">

        <div class="flex flex-col px-6 py-6 space-y-2 text-base font-medium text-gray-100">
            @foreach(\App\Models\Category::where('is_in_menu', true)->get() as $menu)
                <a href="{{ route('category.list',$menu->id) }}" class="group hover:text-yellow-400 hover:bg-white/5 px-3 py-3 rounded-xl transition-all duration-300 flex items-center gap-3 drop-shadow-md">
                    <span class="w-2 h-2 rounded-full bg-gray-500 group-hover:bg-yellow-400 transition-colors"></span>
                    {{ $menu->name }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
@yield('main')

<footer class="border-t border-[var(--border-base)] py-8 text-center text-[var(--text-faint)] text-xs w-full bg-[var(--bg-base)]">
    <div class="flex flex-col items-center justify-center gap-5 max-w-4xl mx-auto px-4">

        <div class="flex items-center gap-6">

            <a href="{{ setting('social_facebook') }}" class="text-[var(--text-muted)] hover:text-blue-500 transition-colors duration-300" aria-label="Facebook">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>

            <a href="{{ setting('social_twitter') }}" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors duration-300" aria-label="X (Twitter)">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>

            <a href="{{ setting('social_instagram') }}" class="text-[var(--text-muted)] hover:text-pink-500 transition-colors duration-300" aria-label="Instagram">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>

            <a href="{{ setting('social_youtube') }}" class="text-[var(--text-muted)] hover:text-red-500 transition-colors duration-300" aria-label="YouTube">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
        </div>

        <p class="text-[var(--text-faint)]">{{ __('footer_text') }}</p>

    </div>
</footer>
<script>
    (function() {
        // تاریخ هدف: ۱۸ شهریور ۱۴۱۹ هجری شمسی
        // معادل با ۹ سپتامبر ۲۰۴۰ میلادی
        // ماه‌ها در جاوااسکریپت: ۰ = ژانویه, ۸ = سپتامبر
        const targetDate = new Date(2040, 8, 9, 0, 0, 0).getTime();

        const yearsElement = document.getElementById('years');
        const monthsElement = document.getElementById('months');
        const daysElement = document.getElementById('days');
        const hoursElement = document.getElementById('hours');
        const minutesElement = document.getElementById('minutes');
        const secondsElement = document.getElementById('seconds');

        function updateTimer() {
            const now = new Date().getTime();
            let distance = targetDate - now;

            if (distance < 0) {
                // اگر زمان فرا رسید
                yearsElement.innerText = '00';
                monthsElement.innerText = '00';
                daysElement.innerText = '00';
                hoursElement.innerText = '00';
                minutesElement.innerText = '00';
                secondsElement.innerText = '00';

                // تغییر متن هدر به "وعده محقق شد"
                const headerText = document.querySelector('.text-5xl');
                if(headerText) {
                    headerText.innerHTML = '<span class="text-shine">وعده الهی محقق شد</span>';
                }
                return;
            }

            // محاسبات دقیق
            const totalDays = Math.floor(distance / (1000 * 60 * 60 * 24));

            // محاسبه سال (تقریبی با میانگین 365.25 روز)
            const years = Math.floor(totalDays / 365.25);
            const remainingDaysAfterYears = totalDays - Math.floor(years * 365.25);

            // محاسبه ماه (تقریبی با میانگین 30.44 روز)
            const months = Math.floor(remainingDaysAfterYears / 30.44);
            const days = Math.floor(remainingDaysAfterYears - (months * 30.44));

            // محاسبه ساعت، دقیقه و ثانیه
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // به‌روزرسانی DOM
            yearsElement.innerText = years.toString().padStart(2, '0');
            monthsElement.innerText = months.toString().padStart(2, '0');
            daysElement.innerText = days.toString().padStart(2, '0');
            hoursElement.innerText = hours.toString().padStart(2, '0');
            minutesElement.innerText = minutes.toString().padStart(2, '0');
            secondsElement.innerText = seconds.toString().padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    })();
</script>
</body>
</html>
