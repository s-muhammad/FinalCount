@extends('admin.layout')

@section('title', 'تنظیمات')
@section('header', 'تنظیمات سایت')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">فعال‌سازی ماژول‌ها</h3>
            <p class="text-gray-500 text-sm mb-4">ماژول‌های غیرفعال در منو و صفحه اصلی نمایش داده نمی‌شوند</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach(['news' => 'اخبار', 'messages' => 'پیام‌ها', 'media' => 'چندرسانه‌ای', 'articles' => 'مقالات', 'interviews' => 'گفتگوها', 'quotes' => 'نقل‌قول‌ها', 'gallery' => 'گالری', 'contact' => 'ارتباط با ما'] as $key => $label)
                    <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        <div class="relative">
                            <input type="hidden" name="module_active_{{ $key }}" value="0">
                            <input type="checkbox" name="module_active_{{ $key }}" value="1" {{ ($settings["module_active_{$key}"] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" onchange="this.value = this.checked ? '1' : '0'">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">اطلاعات سایت</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نام سایت</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'نگاران' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">توضیحات سایت</label>
                    <input type="text" name="site_description" value="{{ $settings['site_description'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">لوگوی سایت</label>
                    @if(!empty($settings['site_logo']))
                        <div class="mb-3">
                            <img src="{{ Storage::url($settings['site_logo']) }}" alt="لوگوی فعلی" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                        </div>
                    @endif
                    <input type="file" name="site_logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    <input type="hidden" name="site_logo_current" value="{{ $settings['site_logo'] ?? '' }}">
                    <p class="text-xs text-gray-400 mt-1">فقط تصویر (JPG، PNG، WebP). اگر فایلی انتخاب نشود، لوگوی فعلی حفظ می‌شود.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">شمارش معکوس</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ هدف</label>
                    <input type="text" name="countdown_target_date" value="{{ $settings['countdown_target_date'] ?? '2040-09-09' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="2040-09-09">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">عنوان</label>
                    <input type="text" name="countdown_title" value="{{ $settings['countdown_title'] ?? 'شما ۲۵ سال آینده را خواهید دید' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">توضیحات</label>
                    <textarea name="countdown_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ $settings['countdown_description'] ?? 'به حول و قوه الهی، تا ۲۵ سال آینده چیزی به نام رژیم صهیونیستی وجود نخواهد داشت.' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">رنگ پس‌زمینه</label>
                    <input type="color" name="countdown_bg_color" value="{{ $settings['countdown_bg_color'] ?? '#1a4d2e' }}" class="w-full h-11 border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-primary cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تصویر پس‌زمینه (اختیاری)</label>
                    <input type="file" name="countdown_bg_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    <p class="text-xs text-gray-400 mt-1">در صورت انتخاب، روی رنگ پس‌زمینه اعمال می‌شود.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">بنر تبلیغاتی</h3>
            <p class="text-gray-500 text-sm mb-4">در صفحات جزییات (خبر، پیام، چندرسانه‌ای) نمایش داده می‌شود.</p>
            <div class="space-y-4">
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">نمایش بنر تبلیغاتی</span>
                    <div class="relative">
                        <input type="hidden" name="ad_banner_enabled" value="0">
                        <input type="checkbox" name="ad_banner_enabled" value="1" {{ ($settings['ad_banner_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </div>
                </label>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تصویر بنر</label>
                    @if(!empty($settings['ad_banner_image']))
                        <div class="mb-3">
                            <img src="{{ Storage::url($settings['ad_banner_image']) }}" alt="بنر تبلیغاتی" class="h-20 rounded-lg border border-gray-200 object-cover">
                        </div>
                    @endif
                    <input type="file" name="ad_banner_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    <p class="text-xs text-gray-400 mt-1">تصویر کشیده (مثلاً ۴۶۸×۶۰). اگر فایلی انتخاب نشود، بنر فعلی حفظ می‌شود.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">آدرس لینک</label>
                    <input type="url" name="ad_banner_link" value="{{ $settings['ad_banner_link'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="https://example.com">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">شبکه‌های اجتماعی</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">اینستاگرام</label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="https://instagram.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">توییتر</label>
                    <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="https://twitter.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">یوتیوب</label>
                    <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="https://youtube.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تلگرام</label>
                    <input type="url" name="social_telegram" value="{{ $settings['social_telegram'] ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="https://t.me/...">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">متن فوتر</h3>
            <textarea name="footer_text" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ $settings['footer_text'] ?? '' }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">ذخیره تنظیمات</button>
        </div>
    </div>
</form>
@endsection
