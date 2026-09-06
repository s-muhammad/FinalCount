@extends('admin.layout')

@section('title', 'رسانه جدید')
@section('header', 'ایجاد رسانه جدید')

@section('content')
<form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">توضیحات</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div x-show="lang === 'ar'" dir="rtl" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea name="description_ar" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="rtl">{{ old('description_ar') }}</textarea>
                    </div>
                </div>

                <div x-show="lang === 'en'" dir="ltr" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title_en" value="{{ old('title_en') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description_en" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" dir="ltr">{{ old('description_en') }}</textarea>
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
                            <option value="video">ویدیو</option>
                            <option value="audio">صدا</option>
                            <option value="image">تصویر</option>
                            <option value="document">سند</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">دسته‌بندی</label>
                        <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="speech">سخنرانی</option>
                            <option value="interview">گفتگو</option>
                            <option value="documentary">مستند</option>
                            <option value="other">سایر</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="draft">پیش‌نویس</option>
                            <option value="published">منتشر شده</option>
                            <option value="archived">بایگانی</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-primary rounded border-gray-300">
                        <label class="text-sm text-gray-700">ویژه</label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">فایل</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">فایل اصلی</label>
                        <input type="file" name="file" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تصویر بندانگشتی</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.media.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">ذخیره</button>
            </div>
        </div>
    </div>
</form>
@endsection
