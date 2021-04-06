<?php

use App\Http\Controllers\Api\SurahController;
use Illuminate\Support\Facades\Route;

Route::get('surah', [SurahController::class , 'index'])->name('surah.index');
Route::get('surah/{surah}', [SurahController::class , 'show'])->name('surah.show');
