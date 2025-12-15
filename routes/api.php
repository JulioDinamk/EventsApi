<?php

use App\Http\Controllers\ApiManagement\ProvisionApiClient;
use App\Http\Controllers\ApiManagement\ShowApiClient;
use App\Http\Middleware\CheckInternalApiKey;
use Illuminate\Support\Facades\Route;

Route::prefix('internal')->middleware(CheckInternalApiKey::class)->group(function () {
    Route::post('/provision/client', ProvisionApiClient::class);
    Route::get('/client/{id}', ShowApiClient::class);
});
