<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود - پنل مدیریت</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/vazirmatn.css" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-bold text-2xl">ن</span>
                </div>
                <h1 class="text-xl font-bold text-gray-800">پنل مدیریت نگاران</h1>
                <p class="text-gray-500 text-sm mt-1">برای ورود اطلاعات خود را وارد کنید</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    نام کاربری یا رمز عبور اشتباه است
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ایمیل</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-primary" required autofocus>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رمز عبور</label>
                        <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-primary" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary rounded border-gray-300">
                            <span class="text-sm text-gray-600">مرا به خاطر بسپار</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg text-sm font-medium hover:bg-primary-light transition">
                        ورود
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
