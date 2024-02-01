<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\IndexController;

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
Route::get('/', [IndexController::class, 'index']);
Route::get('index', [IndexController::class, 'index']);
Route::get('list/{bankuai_id}', [IndexController::class, 'list']);
Route::get('tiezi/{bankuai_id}/{tie_id}', [IndexController::class, 'tiezi']);

Route::get('fatie/{bankuai_id}', [IndexController::class, 'fatie']);
Route::post('doFatie', [IndexController::class, 'doFatie']);
Route::get('doTieziDelete/{bankuai_id}/{tie_id}', [IndexController::class, 'doTieziDelete']);
Route::get('fatieEdit/{bankuai_id}/{tie_id}', [IndexController::class, 'fatieEdit']);
Route::post('doTieziEdit', [IndexController::class, 'doTieziEdit']);

Route::get('huifu/{bankuai_id}/{tie_id}', [IndexController::class, 'huifu']);
Route::post('doHuifu', [IndexController::class, 'doHuifu']);
Route::get('doHuifuDelete/{huifu_id}/{bankuai_id}/{tie_id}', [IndexController::class, 'doHuifuDelete']);
Route::get('huifuEdit/{huifu_id}/{bankuai_id}/{tie_id}', [IndexController::class, 'huifuEdit']);
Route::post('doHuifuEdit', [IndexController::class, 'doHuifuEdit']);

Route::get('login', [IndexController::class, 'login']);
Route::post('doLogin', [IndexController::class, 'doLogin']);
Route::get('doLogOut', [IndexController::class, 'doLogOut']);
Route::get('register', [IndexController::class, 'register']);
Route::post('doRegister', [IndexController::class, 'doRegister']);
