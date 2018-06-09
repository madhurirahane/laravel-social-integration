<?php

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

Auth::routes();

// Notification
Route::get('/home', 'HomeController@index')->name('home');

Route::get('add/credits', 'HomeController@AddCredits')->name('add-credits');

Route::post('/markAsReadUserNotification', ['as' => '/markAsReadUserNotification', 'uses' => 'HomeController@markAsReadUserNotification'])->name('markAsReadUserNotification');
Route::post('/deleteUserNotification', ['as' => '/deleteUserNotification', 'uses' => 'HomeController@deleteUserNotification'])->name('deleteUserNotification');
Route::post('/getNotification', ['as' => '/getNotification', 'uses' => 'HomeController@getNotification'])->name('getNotification');

//Login With Socialite
Route::get('login/socialite', 'HomeController@loginViaGoogle')->name('login-via-socialite');

Route::get('login/google', 'HomeController@redirectToProvider')->name('login-google');
Route::get('login/facebook', 'HomeController@redirectToFacebookProvider')->name('login-facebook');
Route::get('login/linkedin', 'HomeController@redirectToLinkedInProvider')->name('login-linkedin');

Route::get('login-google-callback', 'HomeController@responseFromGoogleCallback')->name('login-google-callback');
Route::get('login-linkedin-callback', 'HomeController@responseFromLinkedInCallback')->name('login-linkedin-callback');
Route::get('login-facebook-callback', 'HomeController@responseFromFacebookCallback')->name('login-facebook-callback');


