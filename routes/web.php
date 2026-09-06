<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['fa', 'ar', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale.change');
Route::get('/news', [PublicController::class, 'news'])->name('public.news');
Route::get('/news/{news}', [PublicController::class, 'newsShow'])->name('public.news.show');
Route::get('/messages', [PublicController::class, 'messages'])->name('public.messages');
Route::get('/messages/{message}', [PublicController::class, 'messageShow'])->name('public.messages.show');
Route::get('/media', [PublicController::class, 'media'])->name('public.media');
Route::get('/media/{medium}', [PublicController::class, 'mediaShow'])->name('public.media.show');
Route::get('/culture', [PublicController::class, 'culture'])->name('public.culture');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'contactStore'])->name('public.contact.store');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('news', NewsController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('media', MediaController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('interviews', InterviewController::class);
    Route::resource('quotes', QuoteController::class);
    Route::resource('gallery', GalleryController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
