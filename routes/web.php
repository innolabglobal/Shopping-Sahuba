<?php

use App\Mail\TestMail;
use App\Mail\Client\SignUpEmail;

Route::get('/', 'PageController@index');
Route::get('shop/{slug}/{id}', 'PageController@shop');
Route::get('product/{slug}/{id}', 'PageController@product');
Route::get('search/', 'SearchController@search');

Route::group(['namespace' => 'Utils'], function () {
    Route::get('/command/{command}', 'CommandController@command');
    Route::resource('medias', 'MediaController');
    Route::get('sitemap.xml', 'SitemapController@generate');
});

Route::resource('categories', 'Utils\CategoryController');


Route::group(['prefix' => 'admin', 'middleware' => ['auth', "role:admin"]], function () {
    Route::get('/command/{command}', 'CommandController@command');
    Route::get('/', 'Admin\AdminController@index');
    Route::resource('/users', 'Admin\UserController')->only(["index", "edit", "update"]);
    Route::get('jobs', 'System\JobsController');
});

Route::group(['prefix' => 'shopping', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', function () {
        return view('bend.page.index');
    });
    Route::resource('/products', 'ProductController');
    Route::get('settings', 'SettingController@general');
    Route::post('settings', 'SettingController@store');
});





Route::get('test', function () {
    // return new TestMail();
    // \App\Jobs\EmailJobs::dispatch();
    return new SignUpEmail();
    return 'ok';
});


// Authentication
Auth::routes(['verify' => true]);

Route::get('auth/email-authenticate/{token}', 'Auth\AuthController@authenticateEmail');
Route::get('oauth/{driver}', 'Auth\SocialAuthController@redirectToProvider')->name('social.oauth');
Route::get('oauth/{driver}/callback', 'Auth\SocialAuthController@handleProviderCallback')->name('social.callback');
Route::get('/home', 'HomeController@index')->name('home');
