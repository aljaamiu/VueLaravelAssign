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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function() {
    //
    Route::get('getall/', [App\Http\Controllers\apiController::class, 'getall'])->name('getall');
    Route::get('getone/{id}', [App\Http\Controllers\apiController::class, 'getone'])->name('getone');
    Route::get('remove/{id}', [App\Http\Controllers\apiController::class, 'remove'])->name('remove');
    Route::post('update/', [App\Http\Controllers\apiController::class, 'update'])->name('getall');
    Route::post('save/', [App\Http\Controllers\apiController::class, 'save'])->name('save');
});
