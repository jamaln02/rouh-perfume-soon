<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::post('/survey/submit', [SurveyController::class, 'submit']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working successfully! 🚀']);
});