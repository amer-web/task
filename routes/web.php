<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ZipController;
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
Route::get('login', [AuthController::class, "loginView"])->name('login');
Route::post('login', [AuthController::class, "login"])->name('login');
Route::get('register', [AuthController::class, "registerView"])->name('register');
Route::post('register', [AuthController::class, "register"])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, "logout"])->name('logout');
    Route::get("zip-form", [ZipController::class, "zipUpload"]);
    Route::post("upload-zip", [ZipController::class, "uploadAndExtract"]);
    Route::get("files", [ZipController::class, "index"])->name('zip.index');
    Route::get("json", [ZipController::class, "json"])->name('zip.json');
    Route::post("files/delete/{id}", [ZipController::class, "delete"])->name('zip.delete');

});
