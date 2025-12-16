<?php

use App\Http\Controllers\External\AuthenticateCustomer;
use App\Http\Controllers\External\Members\MemberInformation;
use App\Http\Controllers\Internal\CreateCustomer;
use App\Http\Controllers\Internal\EditCustomer;
use App\Http\Controllers\Internal\ShowCustomer;
use App\Http\Middleware\CheckInternalApiKey;
use Illuminate\Support\Facades\Route;

Route::prefix('internal')->middleware(CheckInternalApiKey::class)->group(function () {
    Route::post('provision/client', CreateCustomer::class);
    Route::get('client/{id}', ShowCustomer::class);
    Route::patch('client/{id}', EditCustomer::class);
});

Route::prefix('v1')->group(function () {
    Route::post('authenticate', AuthenticateCustomer::class);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
   Route::get('user/{eventUuid}', MemberInformation::class);
});
