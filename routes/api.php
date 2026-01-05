<?php

use App\Http\Controllers\External\AuthenticateCustomer;
use App\Http\Controllers\External\CustomerEvents;
use App\Http\Controllers\External\Members\MemberInformation;
use App\Http\Controllers\Internal\AddEvent;
use App\Http\Controllers\Internal\CreateCustomer;
use App\Http\Controllers\Internal\EditCustomer;
use App\Http\Controllers\Internal\RemoveEvent;
use App\Http\Controllers\Internal\ShowCustomer;
use App\Http\Middleware\CheckInternalApiKey;
use Illuminate\Support\Facades\Route;

Route::prefix('internal')->middleware(CheckInternalApiKey::class)->group(function () {
    Route::post('provision/client', CreateCustomer::class);
    Route::get('client/{id}', ShowCustomer::class);
    Route::patch('client/{id}', EditCustomer::class);
    Route::post('client/{id}/event', AddEvent::class);
    Route::delete('client/{id}/event', RemoveEvent::class);
});

Route::prefix('v1')->group(function () {
    Route::post('authenticate', AuthenticateCustomer::class);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('events', CustomerEvents::class);

    Route::get('user/{eventUuid}', MemberInformation::class);
});
