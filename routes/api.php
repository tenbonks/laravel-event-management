<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('events', EventController::class);

Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped();

Route::fallback(function () {
    return response()->json(['message' => 'This endpoint was not found!'], 404);
});
