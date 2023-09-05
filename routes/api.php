<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MoviesController;

Route::post('/movies/create-file', [MoviesController::class, 'createFile']);
Route::post('/movies/create', [MoviesController::class, 'create']);

Route::put('/movies/update', [MoviesController::class, 'update']);

Route::get('/movies/list/{id}', [MoviesController::class, 'list']);
Route::get('/movies/list-all', [MoviesController::class, 'listAll']);

Route::delete('/movies/delete/{id}', [MoviesController::class, 'delete']);