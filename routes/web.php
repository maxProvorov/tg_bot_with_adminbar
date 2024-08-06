<?php

use App\Http\Controllers\BotController;
use DefStudio\Telegraph\Models\TelegraphBot;


Route::post('/webhook', [BotController::class, 'handle']);
