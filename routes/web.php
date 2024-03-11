<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/product', [ProductController::class, 'show']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'delete']);

    Route::get('/order', [OrderController::class, 'show']);
    Route::get('/order/{orderId}', [OrderController::class, 'showByIdOrder']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::put('/order', [OrderController::class, 'update']);
    Route::delete('/order', [OrderController::class, 'delete']);

    
});

Route::get('/login', [AuthController::class, 'loginIndex'])->name('login');
Route::post('/login', [AuthController::class, 'loginLogic']);

Route::post('/logout', [AuthController::class, 'logoutLogic']);

Route::get('/register', [AuthController::class, 'registerIndex']);
Route::post('/register', [AuthController::class, 'registerLogic']);
