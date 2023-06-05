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
|/
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('user/register', 'AuthController@register');
Route::post('user/login', 'UserController@login');

Route::get('logout', 'AuthController@logout');

Route::get('test/get_in_exchanges_usdt_glassnode', 'SystemController@get_in_exchanges_usdt_glassnode');


Route::post('user/nickname', 'UserController@checknickname');


Route::group([
    // 'middleware' => ['auth']
], function () {




    Route::post('user/update', 'UserController@save_user');
    Route::post('user/notifications', 'UserController@save_notifications');

    Route::post('user/reading_club', 'UserController@reading_club');
    Route::post('user/search', 'UserController@search_readers');
    Route::post('user/remove_reader', 'UserController@remove_reader');
    Route::post('user/add_reader', 'UserController@add_reader');


    Route::post('filters/update_filter', 'StockController@update_filter');
    Route::post('filters/list', 'StockController@list_filters');

    Route::post('sources/update_rank', 'SourceController@update_rank');
    Route::post('sources/update_rss', 'SourceController@update_rss');
    Route::post('sources/update_auto', 'SourceController@update_auto');

    Route::post('news/get_news', 'NewsletterController@get_news');
    Route::post('news/share', 'NewsletterController@share');
    Route::post('/share/search', 'ShareController@search');
    Route::post('/share/remove', 'ShareController@remove');




    Route::post('watchlist/update_filter', 'WatchlistController@update_filter');
    Route::post('watchlist/get_filters_count', 'WatchlistController@get_filters_count');

    Route::post('sources/list', 'SourceController@list');
    Route::post('sources/list_sources_category', 'SourceController@list_sources_category');






//User
Route::post('user/list', 'UserController@get_list');
Route::get('user/info', 'UserController@get_user');
Route::post('user/info', 'UserController@get_user');



});
