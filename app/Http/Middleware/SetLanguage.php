<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    public function handle(Request $request, Closure $next)
    {
        // ۱. بررسی می‌کنیم آیا کلیدی به نام 'locale' در سشن وجود دارد؟
        if (Session::has('locale')) {

            // ۲. اگر وجود داشت، مقدار آن را از سشن می‌گیریم (مثلا 'ar' یا 'en')
            $locale = Session::get('locale');

            // ۳. زبان هسته لاراول را به این مقدار تغییر می‌دهیم
            App::setLocale($locale);
        }

        // اجازه می‌دهیم درخواست به مسیر خودش ادامه دهد
        return $next($request);
    }
}
