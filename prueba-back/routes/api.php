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

Route::resource('contacts','ContactController');
Route::post('user-login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
//Route::post('user-register', 'AuthController@register');
Route::resource('users','UserController');
