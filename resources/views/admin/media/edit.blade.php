@extends('admin.layout')

@section('title', 'ویرایش رسانه')
@section('header', 'ویرایش رسانه')

@section('content')
<form action="{{ route('admin.media.update', $medium) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div x-data="{ lang: 'fa' }" class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <button type="button" @click="lang = 'fa'" :class="lang === 'fa' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇮🇷 فارسی</button>
                    <button type="button" @click="lang = 'ar'" :class="lang === 'ar' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇸🇦 العربية</button>
                    <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇬🇧 English</button>
                </div>

                <div x-show="lang === 'fa'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان</label>
                        <input type="text" name="title" value="{{ old('title', $medium->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">توضیحات</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('description', $medium->description) }}</textarea>
                    </div>
                </div>

                <div x-show="lang === 'ar'" dir="rtl" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $medium->title_ar) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea name="description_ar" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="rtl">{{ old('description_ar', $medium->description_ar) }}</textarea>
                    </div>
                </div>

                <div x-show="lang === 'en'" dir="ltr" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $medium->title_en) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description_en" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="ltr">{{ old('description_en', $medium->description_en) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">تنظیمات</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="video" {{ old('type', $medium->type) === 'video' ? 'selected' : '' }}>ویدیو</option>
                            <option value="audio" {{ old('type', $medium->type) === 'audio' ? 'selected' : '' }}>صدا</option>
                            <option value="image" {{ old('type', $medium->type) === 'image' ? 'selected' : '' }}>تصویر</option>
                            <option value="document" {{ old('type', $medium->type) === 'document' ? 'selected' : '' }}>سند</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">دسته‌بندی</label>
                        <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="speech" {{ old('category', $medium->category) === 'speech' ? 'selected' : '' }}>سخنرانی</option>
                            <option value="interview" {{ old('category', $medium->category) === 'interview' ? 'selected' : '' }}>گفتگو</option>
                            <option value="documentary" {{ old('category', $medium->category) === 'documentary' ? 'selected' : '' }}>مستند</option>
                            <option value="other" {{ old('category', $medium->category) === 'other' ? 'selected' : '' }}>سایر</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="draft" {{ old('status', $medium->status) === 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            <option value="published" {{ old('status', $medium->status) === 'published' ? 'selected' : '' }}>منتشر شده</option>
                            <option value="archived" {{ old('status', $medium->status) === 'archived' ? 'selected' : '' }}>بایگانی</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $medium->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300">
                        <label class="text-sm text-gray-700">ویژه</label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">فایل</h3>
                <div class="space-y-3">
                    @if($medium->file)
                        <div class="text-sm text-gray-600 mb-2">فایل فعلی: {{ basename($medium->file) }}</div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">فایل اصلی</label>
                        <input type="file" name="file" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                    @if($medium->thumbnail)
                        <img src="{{ Storage::url($medium->thumbnail) }}" class="w-full h-32 object-cover rounded-lg mb-2">
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تصویر بندانگشتی</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.media.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">بروزرسانی</button>
            </div>
        </div>
    </div>
</form>
@endsection
