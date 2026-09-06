<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');
        
        if ($locale && in_array($locale, ['fa', 'ar', 'en'])) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        } elseif (session('locale')) {
            App::setLocale(session('locale'));
        }
        
        return $next($request);
    }
}
