<?php

use App\Http\Middleware\UserHasRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DepartmentController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => UserHasRole::class . ':admin'], function () {
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
        Route::resource('candidates', CandidateController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::group(['middleware' => UserHasRole::class . ':hr,lm,final'], function () {
        Route::get('interview/{candidate}', [CandidateController::class, 'interview'])->name('candidates.interview');
        Route::post('interview/{candidate}', [CandidateController::class, 'saveInterview'])->name('candidates.save-interview');
    });

    Route::resource('candidates', CandidateController::class)->only(['index', 'show']);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function () {
        return view('admin.pages.auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Lưu dữ liệu từ form vào database
Route::post('/save-word-data', [App\Http\Controllers\WordToHtmlController::class, 'saveWordData'])->name('save.word.data');

// Tạo và tải xuống file Word từ dữ liệu đã lưu (tuỳ chọn)
Route::get('/generate-word/{id}', [App\Http\Controllers\WordToHtmlController::class, 'generateWordFromSavedData'])->name('generate.word');

