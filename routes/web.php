<?php

use App\Http\Controllers\{AdvisorController, BankingController, BusController, CityController, NewsroomController};
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::prefix('challenges')->group(function () {
    Route::get('banking', [BankingController::class, 'index'])->name('banking');
    Route::post('banking/identity', [BankingController::class, 'identity'])->name('banking.identity');
    Route::get('banking/investigation', [BankingController::class, 'investigation'])->name('banking.investigation');
    Route::get('bus', [BusController::class, 'index'])->name('bus');
    Route::post('bus', [BusController::class, 'update'])->name('bus.update');
    Route::get('newsroom', [NewsroomController::class, 'index'])->name('newsroom');
    Route::get('city', [CityController::class, 'index'])->name('city');
    Route::post('city', [CityController::class, 'store'])->name('city.store');
    Route::get('ai-advisor', [AdvisorController::class, 'index'])->name('ai-advisor');
    Route::post('ai-advisor', [AdvisorController::class, 'store'])->name('ai-advisor.store');
});
