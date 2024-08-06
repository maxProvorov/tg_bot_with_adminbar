<?php

use App\Http\Controllers\BotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(BotController::class)->group(function () {
    Route::post('/telegram/bot', 'botController');
});

