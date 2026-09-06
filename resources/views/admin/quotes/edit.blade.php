@extends('admin.layout')

@section('title', 'ویرایش نقل‌قول')
@section('header', 'ویرایش نقل‌قول')

@section('content')
<form action="{{ route('admin.quotes.update', $quote) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div x-data="{ lang: 'fa' }" class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <h3 class="font-bold text-gray-800">متن نقل‌قول</h3>
            <div class="flex gap-1 mr-auto">
                <button type="button" @click="lang = 'fa'" :class="lang === 'fa' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇮🇷 فارسی</button>
                <button type="button" @click="lang = 'ar'" :class="lang === 'ar' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇸🇦 العربية</button>
                <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇬🇧 English</button>
            </div>
        </div>

        <div class="space-y-4">
            {{-- Persian (fa) --}}
            <div x-show="lang === 'fa'">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">متن</label>
                    <textarea name="body" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>{{ old('body', $quote->body) }}</textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">منبع</label>
                    <input type="text" name="source" value="{{ old('source', $quote->source) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            {{-- Arabic (ar) --}}
            <div x-show="lang === 'ar'">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">المتن</label>
                    <textarea name="body_ar" rows="4" dir="rtl" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body_ar', $quote->body_ar) }}</textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">المصدر</label>
                    <input type="text" name="source_ar" value="{{ old('source_ar', $quote->source_ar) }}" dir="rtl" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            {{-- English (en) --}}
            <div x-show="lang === 'en'">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea name="body_en" rows="4" dir="ltr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body_en', $quote->body_en) }}</textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <input type="text" name="source_en" value="{{ old('source_en', $quote->source_en) }}" dir="ltr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ</label>
                <input type="text" name="date" value="{{ old('date', $quote->date) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تصویر</label>
                @if($quote->image)
                    <img src="{{ Storage::url($quote->image) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $quote->is_active) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300">
                <label class="text-sm text-gray-700">فعال</label>
            </div>
        </div>
    </div>
    <div class="flex gap-3 mt-6">
        <a href="{{ route('admin.quotes.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">بروزرسانی</button>
    </div>
</form>
@endsection
