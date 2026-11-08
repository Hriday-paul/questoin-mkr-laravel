<?php

use App\Http\Controllers\Admin\AdminPortalController;
use App\Http\Controllers\Admin\QuestionBankController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');
});

Route::middleware(['auth', 'verified', 'role:user'])->group(function (): void {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function (): void {
    Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');

    Route::get('/classes', [AdminPortalController::class, 'classes'])->name('classes.index');
    Route::post('/classes', [AdminPortalController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{academicClass}', [AdminPortalController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{academicClass}', [AdminPortalController::class, 'destroyClass'])->name('classes.destroy');

    Route::get('/subjects', [AdminPortalController::class, 'subjects'])->name('subjects.index');
    Route::post('/subjects', [AdminPortalController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{subject}', [AdminPortalController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [AdminPortalController::class, 'destroySubject'])->name('subjects.destroy');

    Route::get('/chapters', [AdminPortalController::class, 'chapters'])->name('chapters.index');
    Route::post('/chapters', [AdminPortalController::class, 'storeChapter'])->name('chapters.store');
    Route::put('/chapters/{chapter}', [AdminPortalController::class, 'updateChapter'])->name('chapters.update');
    Route::delete('/chapters/{chapter}', [AdminPortalController::class, 'destroyChapter'])->name('chapters.destroy');

    Route::get('/questions', function () {
        return redirect()->route('admin.questions.short.index');
    })->name('questions.index');

    Route::prefix('questions')->name('questions.')->group(function (): void {
        Route::get('/short', [QuestionBankController::class, 'shortIndex'])->name('short.index');
        Route::post('/short', [QuestionBankController::class, 'shortStore'])->name('short.store');
        Route::put('/short/{shortQuestion}', [QuestionBankController::class, 'shortUpdate'])->name('short.update');
        Route::delete('/short/{shortQuestion}', [QuestionBankController::class, 'shortDestroy'])->name('short.destroy');

        Route::get('/mcq', [QuestionBankController::class, 'mcqIndex'])->name('mcq.index');
        Route::post('/mcq', [QuestionBankController::class, 'mcqStore'])->name('mcq.store');
        Route::put('/mcq/{mcqQuestion}', [QuestionBankController::class, 'mcqUpdate'])->name('mcq.update');
        Route::delete('/mcq/{mcqQuestion}', [QuestionBankController::class, 'mcqDestroy'])->name('mcq.destroy');

        Route::get('/creative', [QuestionBankController::class, 'creativeIndex'])->name('creative.index');
        Route::post('/creative', [QuestionBankController::class, 'creativeStore'])->name('creative.store');
        Route::put('/creative/{creativeQuestion}', [QuestionBankController::class, 'creativeUpdate'])->name('creative.update');
        Route::delete('/creative/{creativeQuestion}', [QuestionBankController::class, 'creativeDestroy'])->name('creative.destroy');

        Route::delete('/{type}/{id}/figure/{field}', [QuestionBankController::class, 'destroyFigure'])->name('figure.destroy');
    });

    Route::get('/users', [AdminPortalController::class, 'users'])->name('users.index');
    Route::patch('/users/{user}/verify', [AdminPortalController::class, 'verifyUser'])->name('users.verify');
    Route::delete('/users/{user}', [AdminPortalController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/organization', [AdminPortalController::class, 'organization'])->name('organization');
    Route::put('/organization', [AdminPortalController::class, 'updateOrganization'])->name('organization.update');
});
