<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MyArchiveController;
use App\Http\Controllers\SmartRulesController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('switch/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-archive', [MyArchiveController::class, 'index'])->name('my-archive');
    
    Route::get('/smart-rules', [SmartRulesController::class, 'index'])->name('smart-rules');
    Route::post('/smart-rules', [SmartRulesController::class, 'store'])->name('smart-rules.store');
    Route::delete('/smart-rules/{smartRule}', [SmartRulesController::class, 'destroy'])->name('smart-rules.destroy');
    
    Route::resource('folders', \App\Http\Controllers\FolderController::class)->except(['create', 'show', 'edit']);
    
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::resource('users', UserController::class)->except(['index', 'create', 'edit']);
    
    // Resource-style routes for CRUD
    Route::resource('documents', DocumentController::class)->except(['create']);
    Route::post('/documents/bulk', [DocumentController::class, 'bulkStore'])->name('documents.bulk');
});

// To fix "Route [register] not defined" error, redirect it to login or just define it
Route::get('/register', function() {
    return redirect()->route('login');
})->name('register');
