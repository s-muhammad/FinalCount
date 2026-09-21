@extends('admin.layout')

@section('title', 'منبع RSS جدید')
@section('header', 'افزودن منبع RSS')

@section('content')
<form action="{{ route('admin.rss.store') }}" method="POST" class="max-w-2xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">نام منبع</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">آدرس RSS</label>
            <input type="url" name="url" value="{{ old('url') }}" dir="ltr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" placeholder="https://example.com/feed.xml" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">زبان منبع</label>
            <select name="language" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                <option value="fa" {{ old('language') === 'fa' ? 'selected' : '' }}>فارسی</option>
                <option value="ar" {{ old('language') === 'ar' ? 'selected' : '' }}>عربی</option>
                <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>انگلیسی</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300">
            <label class="text-sm text-gray-700">فعال</label>
        </div>
        <p class="text-xs text-gray-500">خبرهای ایمپورت‌شده ابتدا به‌صورت «پیش‌نویس» ذخیره می‌شوند و پس از ترجمه با هوش مصنوعی، برای بازبینی در دسترس خواهند بود.</p>
    </div>
    <div class="flex gap-3 mt-6">
        <a href="{{ route('admin.rss.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">ذخیره</button>
    </div>
</form>
@endsection