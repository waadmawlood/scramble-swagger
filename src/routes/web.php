<?php

use Illuminate\Support\Facades\Route;
use Waad\ScrambleSwagger\Controllers\ScrambleSwaggerController;

Route::prefix(config('scramble-swagger.url'))->group(function () {
    Route::get('/', [ScrambleSwaggerController::class, 'show'])->name('scramble-swagger.show');
    Route::get('/json', [ScrambleSwaggerController::class, 'responseJson'])->name('scramble-swagger.responseJson');
});
