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
use App\Http\Controllers\Admin\RssFeedController;
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
Route::get('/people/{person}', [PublicController::class, 'peopleShow'])->name('public.people.show');
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
    Route::resource('people', \App\Http\Controllers\Admin\PeopleController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('rss', [RssFeedController::class, 'index'])->name('rss.index');
    Route::get('rss/create', [RssFeedController::class, 'create'])->name('rss.create');
    Route::post('rss', [RssFeedController::class, 'store'])->name('rss.store');
    Route::get('rss/{rss}/edit', [RssFeedController::class, 'edit'])->name('rss.edit');
    Route::put('rss/{rss}', [RssFeedController::class, 'update'])->name('rss.update');
    Route::delete('rss/{rss}', [RssFeedController::class, 'destroy'])->name('rss.destroy');
    Route::get('rss/logs', [RssFeedController::class, 'logs'])->name('rss.logs');
    Route::post('rss/run', [RssFeedController::class, 'runNow'])->name('rss.run');
    Route::post('rss/{rssImport}/reject', [RssFeedController::class, 'reject'])->name('rss.reject');
    Route::post('rss/{rssImport}/convert', [RssFeedController::class, 'convert'])->name('rss.convert');
});

require __DIR__.'/auth.php';
