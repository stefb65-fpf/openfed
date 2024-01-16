<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/ajax/acceptationCookies', [App\Http\Controllers\PageController::class, 'acceptationCookies'])->name('ajax.acceptationCookies');
Route::post('/ajax/inscriptionOpenfed', [App\Http\Controllers\UtilisateurController::class, 'inscriptionOpenfed'])->name('ajax.inscriptionOpenfed');
Route::post('/ajax/forgotId', [App\Http\Controllers\UtilisateurController::class, 'forgotId'])->name('ajax.forgotId');
Route::post('/ajax/checkLoginOpen', [App\Http\Controllers\UtilisateurController::class, 'checkLoginOpen'])->name('ajax.checkLoginOpen');
Route::post('/ajax/savePhotoOpen', [App\Http\Controllers\PhotoController::class, 'savePhotoOpen'])->name('ajax.savePhotoOpen');
Route::post('/ajax/deletePhotoOpen', [App\Http\Controllers\PhotoController::class, 'deletePhotoOpen'])->name('ajax.deletePhotoOpen');
Route::post('/ajax/updateTitlePhotoOpen', [App\Http\Controllers\PhotoController::class, 'updateTitlePhotoOpen'])->name('ajax.updateTitlePhotoOpen');
