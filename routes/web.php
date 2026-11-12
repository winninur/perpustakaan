<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// Route Middleware Auth (Semua Fitur Utama Terproteksi Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('users', UserController::class);

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{book}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/books/catalog', [BookController::class, 'catalog'])->name('books.catalog');

    // Route Peminjaman & Persetujuan
    Route::post('loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::post('loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    // Route Profil Saya
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route Tambah Ulasan & Komentar Buku (Fitur Baru)
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});