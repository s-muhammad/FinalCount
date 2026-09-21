@php
    $adEnabled = \App\Models\Setting::getValue('ad_banner_enabled', '0') === '1';
    $adImage = \App\Models\Setting::getValue('ad_banner_image', '');
    $adLink = \App\Models\Setting::getValue('ad_banner_link', '');
@endphp

@if($adEnabled && $adImage)
    <a href="{{ $adLink ?: '#' }}" target="_blank" rel="noopener nofollow" class="block overflow-hidden rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition group">
        <img src="{{ Storage::url($adImage) }}" alt="تبلیغات" class="w-full object-cover group-hover:opacity-95 transition">
    </a>
@endif