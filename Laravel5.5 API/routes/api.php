<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//List articles

Route::resource('articles','ArticleControler');

//List single article
Route::get('article/{i}', 'ArticleControler@show');

//Create a new article
Route::post('article', 'ArticleControler@store');

//Update article
Route::put('article', 'ArticleControler@store');

//Delete article
Route::delete('article/{id}', 'ArticleControler@destroy');


