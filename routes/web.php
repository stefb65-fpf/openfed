<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[App\Http\Controllers\PageController::class, 'accueil'])->name('accueil');
Route::get('/logout',[App\Http\Controllers\UtilisateurController::class, 'logout'])->name('logout');
Route::get('/inscription',[App\Http\Controllers\PageController::class, 'inscription'])->name('inscription');
Route::get('/resultats',[App\Http\Controllers\PageController::class, 'resultats'])->name('resultats');
Route::get('/confirmation_email/{crypt}',[App\Http\Controllers\UtilisateurController::class, 'confirmation_email'])->name('confirmation_email');
Route::post('/photos/upload/{identifiant}/{competition_id}/{position}', [App\Http\Controllers\PhotoController::class, 'upload']);
