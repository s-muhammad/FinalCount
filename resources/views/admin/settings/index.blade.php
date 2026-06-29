@extends('admin.layout.app')
@section('main')
    @php
        $translation_keys = [
            'site_title' => 'عنوان سایت',
            'site_image' => 'تصویر هدر سایت',
            'site_description' => 'توضیحات سایت',
            'site_logo' => 'لوگوی سایت',
            'site_favicon' => 'فاوآیکون سایت',
            'site_email' => 'ایمیل سایت',
            'site_phone' => 'شماره تلفن',
            'site_address' => 'آدرس سایت',
            'site_active' => 'وضعیت سایت',
            'maintenance_msg' => 'پیغام خطا',
            'seo_meta_keywords' => 'کلمات کلیدی سئو',
            'seo_meta_author' => 'نویسنده سئو',
            'seo_og_image' => 'تصویر Open Graph',
            'social_github'=>'لینک گیت هاب',
            'social_linkedin'=>'لینک لینکدین',
            'social_instagram'=>'لینک اینستاگرام',
            'social_youtube'=>'لینک یوتیوب',
        ];

        $translation_groups = [
            'general' => ['name' => 'تنظیمات عمومی', 'icon' => 'fas fa-cogs', 'color' => 'blue'],
            'social' => ['name' => 'شبکه‌های اجتماعی', 'icon' => 'fas fa-share-alt', 'color' => 'purple'],
            'seo' => ['name' => 'تنظیمات سئو', 'icon' => 'fas fa-chart-line', 'color' => 'green'],
        ];

        // تعیین تب فعال اولیه (مثلاً 'general')
        $active_tab = request()->get('tab', 'general');
    @endphp

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-10">

        {{-- عنوان صفحه --}}
        <div class="flex items-center mb-8 justify-between border-b pb-4">
            <h2 class="text-3xl font-extrabold text-gray-800">
                <i class="fas fa-sliders-h text-blue-600 ml-3"></i>
                مدیریت تنظیمات سایت
            </h2>
        </div>

        {{-- اعلان موفقیت (با طراحی بهتر) --}}
        @if(session('success'))
            <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-8 shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-xl ml-3"></i>
                    <p class="font-semibold text-sm">تنظیمات با موفقیت ذخیره شد.</p>
                </div>
            </div>
        @endif

        {{-- کانتینر اصلی فرم --}}
        <div class="bg-white rounded-2xl shadow-xl p-0">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('post')

                {{-- ناوبری تب‌ها --}}
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-2 space-x-reverse px-8 pt-4" aria-label="Tabs">
                        @foreach($translation_groups as $key => $group_data)
                            @php
                                $isActive = $key === $active_tab;
                                $baseClasses = 'px-4 py-3 font-semibold text-sm rounded-t-lg transition-colors duration-200 flex items-center gap-2';
                                $activeClasses = 'text-white bg-' . $group_data['color'] . '-600 shadow-lg';
                                $inactiveClasses = 'text-gray-600 hover:text-gray-800 hover:bg-gray-100';
                            @endphp
                            <a href="?tab={{ $key }}"
                               class="{{ $baseClasses }} {{ $isActive ? $activeClasses : $inactiveClasses }}">
                                <i class="{{ $group_data['icon'] }}"></i>
                                {{ $group_data['name'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                {{-- محتوای تب‌ها --}}
                <div class="p-8">
                    @foreach($settings as $group => $items)
                        @php
                            $group_data = $translation_groups[$group] ?? ['name' => ucfirst($group), 'icon' => 'fas fa-info', 'color' => 'gray'];
                        @endphp
                        {{-- فرض می‌کنیم منطق نمایش/پنهان‌سازی با JS و بر اساس کوئری پارامتر (tab) مدیریت می‌شود --}}
                        <div id="tab-{{ $group }}" class="{{ $group === $active_tab ? 'block' : 'hidden' }} space-y-6">

                            <h4 class="text-2xl font-bold text-{{ $group_data['color'] }}-700 mb-6 border-b pb-3">
                                <i class="{{ $group_data['icon'] }} ml-2"></i>
                                {{ $group_data['name'] }}
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($items as $setting)
                                    @php
                                        $label = $translation_keys[$setting->key] ?? $setting->key;
                                    @endphp
                                    {{-- FIELD RENDERING --}}
                                    <div class="{{ $setting->type === 'textarea' ? 'md:col-span-2' : '' }}">
                                        <label for="{{ $setting->key }}" class="block text-gray-700 text-sm font-medium mb-2">{{ $label }}</label>

                                        @if($setting->type === 'text')
                                            <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition duration-300">

                                        @elseif($setting->type === 'image')
                                            <div class="flex items-center space-x-4 space-x-reverse">
                                                @if($setting->value)
                                                    <img src="{{ asset($setting->value) }}" alt="{{ $label }}" class="w-16 h-16 object-contain rounded-lg border-2 border-gray-200 p-1 shadow-md">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-dashed">
                                                        <i class="fas fa-image text-xl"></i>
                                                    </div>
                                                @endif
                                                <input type="file" id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                            </div>

                                        @elseif($setting->type === 'textarea')
                                            <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" rows="4"
                                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition duration-300">{{ $setting->value }}</textarea>

                                        @elseif($setting->type === 'boolean')
                                            <div class="relative">
                                                <select id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                        class="block w-full px-4 py-3 appearance-none rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition duration-300 pr-10">
                                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>غیرفعال</option>
                                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>فعال</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- END FIELD RENDERING --}}
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- دکمه ذخیره --}}
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end rounded-b-2xl">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8
                            rounded-xl shadow-lg shadow-blue-500/50 transition duration-300 transform hover:scale-[1.02] flex items-center gap-2">
                        <i class="fas fa-save"></i> ذخیره کلی تنظیمات
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
