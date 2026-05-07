<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotWebhookController;

// Esta será la URL: http://tu-dominio.test/api/chatbot/webhook
Route::post('/chatbot/webhook', [ChatbotWebhookController::class, 'handle']);
