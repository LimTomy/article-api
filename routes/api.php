<?php

use App\Http\Controllers\ArticleController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
    // get one
    Route::get('article/{article}', [ArticleController::class, 'show']);
    // get all
    Route::get('article', [ArticleController::class, 'index']);
    // store one
    Route::post('article', [ArticleController::class, 'store']);
    //
    Route::put('article/{article}', [ArticleController::class, 'update']);
    // delete
    Route::delete('article/{article}', [ArticleController::class, 'destroy']);
