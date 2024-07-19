<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::get('/blogs',"BlogController@index");
Route::middleware('api_token')->name('api.')->group(function(){
   Route::get('/blogs',"BlogController@index");
   Route::get('/blogs/{id}',"BlogController@show");

   Route::post('/blogs',"BlogController@store");
   Route::put('/blogs/{id}',"BlogController@update");
   Route::delete('/blogs/{id}',"BlogController@destroy");
});