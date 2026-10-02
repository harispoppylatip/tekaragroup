<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Panel;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/proyek/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/tim/{member}', [MemberController::class, 'show'])->name('members.show');
Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{post}', [PostController::class, 'show'])->name('posts.show');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'create'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/keluar', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('panel')->name('panel.')->middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/', Panel\DashboardController::class)->name('dashboard');

    Route::get('/sandi', [Panel\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/sandi', [Panel\PasswordController::class, 'update'])->name('password.update');

    Route::get('/profil', [Panel\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [Panel\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/cv', [Panel\CvController::class, 'edit'])->name('cv.edit');
    Route::put('/cv', [Panel\CvController::class, 'update'])->name('cv.update');

    Route::resource('proyek', Panel\ProjectController::class)
        ->except('show')
        ->parameters(['proyek' => 'project'])
        ->names('projects');

    Route::resource('berita', Panel\PostController::class)
        ->except('show')
        ->parameters(['berita' => 'post'])
        ->names('posts');

    Route::middleware('admin')->group(function () {
        Route::resource('anggota', Panel\MemberController::class)
            ->except('show')
            ->parameters(['anggota' => 'member'])
            ->names('members');
        Route::post('/anggota/{member}/atur-ulang-sandi', [Panel\MemberController::class, 'resetPassword'])->name('members.reset-password');

        Route::get('/pengaturan', [Panel\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan', [Panel\SettingController::class, 'update'])->name('settings.update');
    });
});
