<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authenticated user route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

// Publicly accessible routes
// -- Events
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);

// -- Event Attendees
Route::get('events/{event}/attendees', [AttendeeController::class, 'index']);
Route::get('events/{event}/attendees/{attendee}', [AttendeeController::class, 'show']);

// Authenticated event routes
Route::middleware('auth:sanctum')->group(function () {

    // Events
    Route::post('events', [EventController::class, 'store']);
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::delete('events/{event}', [EventController::class, 'destroy']);

    // Event Attendees
    Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);
    Route::post('events/{event}/attendees', [AttendeeController::class, 'store']);
});

// Fallback route for undefined endpoints
Route::fallback(function () {
    return response()->json(['message' => 'This endpoint was not found!'], 404);
});
