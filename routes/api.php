<?php

use App\Http\Controllers\ApiManagement\ProvisionApiClient;
use App\Http\Controllers\ApiManagement\ShowApiClient;
use App\Http\Controllers\Client\AuthenticateClient;
use App\Http\Controllers\Client\CheckUserInformation;
use App\Http\Middleware\CheckInternalApiKey;
use Illuminate\Support\Facades\Route;

Route::prefix('internal')->middleware(CheckInternalApiKey::class)->group(function () {
    Route::post('provision/client', ProvisionApiClient::class);
    Route::get('client/{id}', ShowApiClient::class);
});

Route::prefix('v1')->group(function () {
    Route::post('authenticate', AuthenticateClient::class);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
   Route::get('user/{eventUuid}', CheckUserInformation::class);
});
