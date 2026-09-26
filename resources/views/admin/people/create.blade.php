@extends('admin.layout')

@section('title', 'افزودن چهره')
@section('header', 'افزودن چهره جدید')

@section('content')
<form action="{{ route('admin.people.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">اطلاعات چهره</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نام</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">درباره / توضیحات</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">تصویر</h3>
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-400 mt-2">تصویر مربعی برای نمایش دایره‌ای بهتر است.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">ویژگی‌ها</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_martyr" value="1" {{ old('is_martyr') ? 'checked' : '' }} class="w-4 h-4 text-green-600 rounded border-gray-300">
                        <span class="text-sm text-gray-700">شهید است</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-gray-300">
                        <span class="text-sm text-gray-700">فعال (نمایش در صفحه‌ی اصلی)</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.people.index') }}" class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">انصراف</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-lg text-sm hover:bg-primary-light">ذخیره</button>
            </div>
        </div>
    </div>
</form>
@endsection