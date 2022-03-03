<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RepositoriesController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/{owner}/{name}/{version?}', [RepositoriesController::class, 'show'])->name('repositories.show');
