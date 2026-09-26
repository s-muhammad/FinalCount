@extends('admin.layout')

@section('title', 'ویرایش پیام')
@section('header', 'ویرایش پیام')

@section('content')
<form action="{{ route('admin.messages.update', $message) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6" x-data="{ lang: 'fa' }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800">اطلاعات اصلی</h3>
                    <div class="flex gap-2">
                        <button type="button" @click="lang = 'fa'" :class="lang === 'fa' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇮🇷 فارسی</button>
                        <button type="button" @click="lang = 'ar'" :class="lang === 'ar' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇸🇦 العربية</button>
                        <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1 rounded-lg text-xs font-medium transition">🇬🇧 English</button>
                    </div>
                </div>

                <div class="space-y-4" x-show="lang === 'fa'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان</label>
                        <input type="text" name="title" value="{{ old('title', $message->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">خلاصه</label>
                        <textarea name="summary" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('summary', $message->summary) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">متن</label>
                        <textarea name="body" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body', $message->body) }}</textarea>
                    </div>
                </div>

                <div class="space-y-4" x-show="lang === 'ar'" dir="rtl">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $message->title_ar) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary text-right">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الملخص</label>
                        <textarea name="summary_ar" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary text-right">{{ old('summary_ar', $message->summary_ar) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">النص</label>
                        <textarea name="body_ar" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary text-right">{{ old('body_ar', $message->body_ar) }}</textarea>
                    </div>
                </div>

                <div class="space-y-4" x-show="lang === 'en'" dir="ltr">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $message->title_en) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                        <textarea name="summary_en" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('summary_en', $message->summary_en) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                        <textarea name="body_en" rows="10" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('body_en', $message->body_en) }}</textarea>
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
                            <option value="speech" {{ old('type', $message->type) === 'speech' ? 'selected' : '' }}>سخنرانی</option>
                            <option value="message" {{ old('type', $message->type) === 'message' ? 'selected' : '' }}>پیام</option>
                            <option value="decree" {{ old('type', $message->type) === 'decree' ? 'selected' : '' }}>حکم</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                            <option value="draft" {{ old('status', $message->status) === 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            <option value="published" {{ old('status', $message->status) === 'published' ? 'selected' : '' }}>منتشر شده</option>
                            <option value="archived" {{ old('status', $message->status) === 'archived' ? 'selected' : '' }}>بایگانی</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ انتشار</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $message->published_at?->format('Y-m-d\TH:i')) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">تصویر</h3>
                @if($message->image)
                    <img src="{{ Storage::url($message->image) }}" class="w-full h-32 object-cover rounded-lg mb-3">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
            </div>

            @include('admin.partials.people-checkboxes')

            <div class="flex gap-3">
                <a href="{{ route('admin.messages.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">بروزرسانی</button>
            </div>
        </div>
    </div>
</form>
@endsection
