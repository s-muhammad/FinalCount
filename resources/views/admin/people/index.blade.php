@extends('admin.layout')

@section('title', 'چهره‌ها و شهدا')
@section('header', 'چهره‌ها و شهدا')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="font-bold text-gray-800 text-xl">مدیریت چهره‌ها و شهدا</h3>
        <p class="text-sm text-gray-500 mt-1">چهره‌های ولایی، علما و شهدا برای نمایش در صفحه‌ی اصلی</p>
    </div>
    <a href="{{ route('admin.people.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-light transition">افزودن چهره</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($people as $person)
        <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col items-center text-center">
            <div class="relative">
                @if($person->image)
                    <img src="{{ Storage::url($person->image) }}" alt="{{ $person->name }}" class="w-24 h-24 rounded-full object-cover border-4 {{ $person->is_martyr ? 'border-green-500' : 'border-gray-200' }}">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-2xl text-gray-400">{{ mb_substr($person->name, 0, 1) }}</div>
                @endif
                @if($person->is_martyr)
                    <span class="absolute -top-1 -right-1 bg-green-600 text-white text-[10px] px-2 py-0.5 rounded-full">شهید</span>
                @endif
            </div>
            <h4 class="font-bold text-gray-800 mt-3">{{ $person->name }}</h4>
            @if($person->is_active)
                <span class="text-[11px] text-green-600 mt-1">فعال</span>
            @else
                <span class="text-[11px] text-gray-400 mt-1">غیرفعال</span>
            @endif
            <div class="text-xs text-gray-400 mt-1 mb-4">
                مقالات: {{ $person->articles_count }} | اخبار: {{ $person->news_count }} | پیام‌ها: {{ $person->messages_count }}
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.people.edit', $person) }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs text-gray-600 hover:bg-gray-50">ویرایش</a>
                <form action="{{ route('admin.people.destroy', $person) }}" method="POST" onsubmit="return confirm('چهره حذف شود؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100">حذف</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center text-gray-500">
            چهره‌ای ثبت نشده است.
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $people->links() }}
</div>
@endsection