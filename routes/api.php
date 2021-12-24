<?php

use App\Http\Controllers\Authentication;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil mengambil data',
        'data' => [
            'user' => $request->user()
        ]
    ], 200);
});

Route::middleware(['auth:sanctum'])->group(function () {
    // note route
    Route::get('/note', [NoteController::class, 'index']);
    Route::post('/note', [NoteController::class, 'store']);
    Route::get('/note/{id}', [NoteController::class, 'show']);
    Route::put('/note/{id}', [NoteController::class, 'update']);
    Route::delete('/note/{id}', [NoteController::class, 'destroy']);
    Route::post('/logout', [Authentication::class, 'logout']);
    // music route
    Route::post('/music', [MusicController::class, 'music']);
    Route::post('/refresh', [MusicController::class, 'refresh']);
});

Route::post('/register', [Authentication::class, 'register']);
Route::post('/login', [Authentication::class, 'login']);

