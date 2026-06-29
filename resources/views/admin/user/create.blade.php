@extends('admin.layout.app')
@section('main')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
        <div class="w-full max-w-2xl mx-auto">
            <div class="flex items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 ml-4">ایجاد کاربر جدید</h2>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle ml-2"></i>
                                <span class="font-bold">خطا:</span>
                            </div>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">نام </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2
                               focus:ring-blue-500 transition-colors duration-200"
                               placeholder="نام کاربر را وارد کنید" required>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">ایمیل </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2
                               focus:ring-blue-500 transition-colors duration-200"
                               placeholder="ایمیل کاربر را وارد کنید" required>
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">رمزعبور</label>
                        <input type="password" id="password" name="password" value="{{ old('password') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2
                               focus:ring-blue-500 transition-colors duration-200"
                               placeholder="رمزعبور را وارد کنید" required>
                    </div>

                    <div class="flex justify-between space-x-4 space-x-reverse">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3
                        px-8 rounded-full shadow-lg transition-colors duration-200">
                            <i class="fas fa-save ml-2"></i> ذخیره
                        </button>
                        <a href="{{ route('admin.user.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white
                        font-semibold py-3 px-8 rounded-full transition-colors duration-200">
                            <i class="fas fa-arrow-right ml-2"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
