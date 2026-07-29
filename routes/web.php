<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class,'index'])->name('home');
Route::get('/blog/{blog}', [IndexController::class,'single'])->name('single');
Route::get('/category/{slug}', [IndexController::class,'category'])->name('category.list');
Route::get('/tag/{slug}', [IndexController::class,'tag'])->name('tag.list');
Route::post('/comment/store', [IndexController::class,'storeComment'])->name('comment.store');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['fa', 'ar', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('change.language');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/',[DashboardController::class, 'index']);
    Route::resource('user',UserController::class);
    Route::resource('blog', BlogController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('comments',CommentsController::class)->only(['index','destroy','update']);
    Route::get('settings/index', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');
});
Route::get('/login',[AuthController::class,'loginForm'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.post');
Route::get('/register',[AuthController::class,'registerForm'])->name('register');
Route::post('/register',[AuthController::class,'register'])->name('register.post');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');
