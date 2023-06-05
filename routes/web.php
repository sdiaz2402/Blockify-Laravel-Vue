<?php

use App\Events\NewUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchlistController;
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
// Route::get("phpinfo",function(){
//     echo("diego");
//     echo phpinfo();
// });

Route::get('/email/newsletter', function () {


    return new App\Mail\Newsletter(1,"mid");
});

Route::get('/email/new_user', function () {

    $user = \App\Models\User::find(1);
    event(new NewUser(1));
    $current_timestamp = \Carbon\Carbon::now()->toDateTimeString();
    return new App\Mail\NewUserEmail(1,$current_timestamp);
});

Route::get('/email/preview_newsletter', function () {

        // $user = \App\Models\User::find(1);

        $controller = new WatchlistController;
        $objects = $controller->list_render(1);
        $objects_favorites = $controller->list_render_favorites(1);
        $objects_unread = $controller->unread_render(1);
            $subject = date("D j M")." - Close Session";
        return View::make('emails.newsletter')
                        ->with(array(
                            "watchlist"=>$objects,
                            "favorites"=>$objects_favorites,
                            "watchlist_unread"=>$objects_unread
                        ));
});

Route::get('/{any}', 'SpaController@index')->where('any', '.*');

// Auth::routes();


Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'UserController@login')->name("login");
    Route::post('register', 'AuthController@register');
    Route::post('create', 'PasswordController@create');
    Route::get('find/{token}', 'PasswordController@find');
    Route::post('reset', 'PasswordController@reset');

});

