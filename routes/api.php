<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotWebhookController;

Route::post('/chatbot/webhook', [ChatbotWebhookController::class, 'handle']);
