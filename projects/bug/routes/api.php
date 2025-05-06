<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BugReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('projects', ProjectController::class)->only(['index','store','show']);
    Route::get('projects/{project}/bugs', [BugReportController::class, 'index']);
    Route::post('projects/{project}/bugs', [BugReportController::class, 'store']);
});