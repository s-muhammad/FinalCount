<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_title') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="preload" href="/font/vazir-font-v16.1.0/Vazir.woff" as="font" type="font/woff2" crossorigin></head>
<body class="bg-gradient-to-tr from-gray-900 via-[#1a1c2e] to-gray-900 min-h-screen flex items-center justify-center p-6 relative overflow-hidden text-white">
<div class="flex flex-col gap-6 w-full max-w-md relative z-10">
    @if($errors->any())
        <div class="bg-red-900/50 border-r-4 border-red-500 text-red-200 p-4 rounded-xl shadow-lg backdrop-blur-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-xl ml-3 text-red-400"></i>
                <span class="font-bold">خطای ورود:</span>
            </div>
            <ul class="mt-3 list-disc list-inside text-sm space-y-1 pr-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('main')
</div>
</body>
</html>
