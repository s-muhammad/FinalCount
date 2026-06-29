@extends('auth.layout')
@section('main')
    <div class="flex-1 bg-white/80 dark:bg-gray-800/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 dark:border-gray-700 transition-colors duration-300">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-gray-800 dark:text-white">ایجاد حساب جدید 🚀</h2>
        </div>
        <form action="{{route('register.post')}}" method="post" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نام</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-white dark:placeholder-gray-400 outline-none transition-all" placeholder="مثال: علی احمدی">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ایمیل</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-white dark:placeholder-gray-400 outline-none transition-all" placeholder="09xxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رمز عبور</label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-white dark:placeholder-gray-400 outline-none transition-all" placeholder="حداقل ۸ کاراکتر">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3.5 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/50 transition-all shadow-lg shadow-blue-500/20 dark:shadow-blue-900/30 mt-4">
                ایجاد حساب کاربری
            </button>
        </form>
{{--        <div class="relative mt-8 mb-6">--}}
{{--            <div class="absolute inset-0 flex items-center">--}}
{{--                <span class="w-full border-t border-gray-200 dark:border-gray-600"></span>--}}
{{--            </div>--}}
{{--            <div class="relative flex justify-center text-sm">--}}
{{--                <span class="px-4 bg-transparent dark:bg-gray-800 text-gray-500 dark:text-gray-400">یا ثبت‌نام با</span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <button type="button" class="w-full flex items-center justify-center gap-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all font-medium hover:shadow-md">--}}
{{--            <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>--}}
{{--                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>--}}
{{--                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>--}}
{{--                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>--}}
{{--            </svg>--}}
{{--            ثبت‌نام با گوگل--}}
{{--        </button>--}}
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-8">
            قبلاً ثبت‌نام کرده‌اید؟
            <a href="{{route('login')}}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline transition-all">وارد شوید</a>
        </p>
    </div>
@endsection
