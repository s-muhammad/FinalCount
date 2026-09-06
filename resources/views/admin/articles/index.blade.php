@extends('admin.layout')

@section('title', 'articles')
@section('header', 'مدیریت articles')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-bold text-gray-800">لیست articles</h3>
        <a href="{{ route('admin.articles.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-light transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            ایجاد جدید
        </a>
    </div>
    <div class="p-6 text-center text-gray-500 py-12">
        <p>لیست articles در اینجا نمایش داده می‌شود</p>
    </div>
</div>
@endsection
