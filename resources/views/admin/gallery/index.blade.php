@extends('admin.layout')

@section('title', 'گالری')
@section('header', 'مدیریت گالری')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-bold text-gray-800">لیست تصاویر</h3>
        <a href="{{ route('admin.gallery.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-light transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            تصویر جدید
        </a>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($gallery as $item)
                <div class="relative group">
                    <img src="{{ Storage::url($item->image) }}" class="w-full h-40 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black/50 rounded-lg opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="p-2 bg-white rounded-lg">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-500 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                    <p class="text-sm text-gray-700 mt-2">{{ $item->title }}</p>
                </div>
            @empty
                <div class="col-span-4 text-center text-gray-500 py-8">تصویری ثبت نشده</div>
            @endforelse
        </div>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $gallery->links() }}
    </div>
</div>
@endsection
