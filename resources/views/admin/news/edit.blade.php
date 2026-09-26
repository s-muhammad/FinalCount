@extends('admin.layout')

@section('title', 'ویرایش خبر')
@section('header', 'ویرایش خبر')

@section('content')
<form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6" x-data="{ lang: 'fa' }">
                <div class="flex items-center gap-2 mb-4 border-b pb-3">
                    <span class="font-bold text-gray-800">اطلاعات اصلی</span>
                    <div class="mr-auto flex gap-1">
                        <button type="button" @click="lang = 'fa'" :class="lang === 'fa' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇮🇷 فارسی</button>
                        <button type="button" @click="lang = 'ar'" :class="lang === 'ar' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇸🇦 العربية</button>
                        <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇬🇧 English</button>
                    </div>
                </div>
                <div class="space-y-4">
                    <div x-show="lang === 'fa'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان</label>
                        <input type="text" name="title" value="{{ old('title', $news->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div x-show="lang === 'ar'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان (عربی)</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $news->title_ar) }}" dir="rtl" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                    <div x-show="lang === 'en'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title (English)</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $news->title_en) }}" dir="ltr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>

                    <div x-show="lang === 'fa'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">خلاصه</label>
                        <textarea name="summary" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('summary', $news->summary) }}</textarea>
                    </div>
                    <div x-show="lang === 'ar'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">خلاصه (عربی)</label>
                        <textarea name="summary_ar" rows="3" dir="rtl" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('summary_ar', $news->summary_ar) }}</textarea>
                    </div>
                    <div x-show="lang === 'en'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Summary (English)</label>
                        <textarea name="summary_en" rows="3" dir="ltr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('summary_en', $news->summary_en) }}</textarea>
                    </div>

                    <div x-show="lang === 'fa'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">متن</label>
                        <textarea name="body" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body', $news->body) }}</textarea>
                    </div>
                    <div x-show="lang === 'ar'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">متن (عربی)</label>
                        <textarea name="body_ar" rows="10" dir="rtl" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body_ar', $news->body_ar) }}</textarea>
                    </div>
                    <div x-show="lang === 'en'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Body (English)</label>
                        <textarea name="body_en" rows="10" dir="ltr" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body_en', $news->body_en) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">انتشار</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>منتشر شده</option>
                            <option value="archived" {{ old('status', $news->status) === 'archived' ? 'selected' : '' }}>بایگانی</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ انتشار</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300">
                        <label class="text-sm text-gray-700">خبر ویژه</label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">تصویر</h3>
                @if($news->image)
                    <img src="{{ Storage::url($news->image) }}" class="w-full h-32 object-cover rounded-lg mb-3">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
            </div>

            @include('admin.partials.people-checkboxes')

            <div class="flex gap-3">
                <a href="{{ route('admin.news.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">بروزرسانی</button>
            </div>
        </div>
    </div>
</form>
@endsection
