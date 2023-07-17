<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DictionaryController;

Route::redirect('/dictionary', '/');

Route::get('/', [DictionaryController::class, 'index']);
Route::get('/dictionary/lookup', [DictionaryController::class, 'lookupWord'])->name('dictionary.lookup');