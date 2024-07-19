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
Route::middleware('api_token')->name('api.')->group(function () {
    //Fetch All Blogs
    Route::get('/blogs', "BlogController@index");

    //Fetch A Blog
    Route::get('/blogs/{id}', "BlogController@show");

    //Create new Blog
    Route::post('/blogs', "BlogController@store");

    //Modify blog
    Route::put('/blogs/{id}', "BlogController@update");

    //Delete blog
    Route::delete('/blogs/{id}', "BlogController@destroy");

    //fetch Blog Post
    //Route::get('/blog-posts/{id}', "BlogPostController@show");


    /*
     * POSTs
     * */

    //Fetch All Posts
    Route::get('/posts', "PostController@index");

    //Fetch A Blog
    Route::get('/posts/{id}', "PostController@show");

    //Create new Blog
    Route::post('/posts', "PostController@store");

    //Modify blog
    Route::put('/posts/{id}', "PostController@update");

    //Delete blog
    Route::delete('/posts/{id}', "PostController@destroy");

    //Like Post
    Route::post('/like', "LikeBlogController@store");

    //Create new comment
    Route::post('/comment', "BlogCommentController@store");
});