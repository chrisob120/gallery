<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * Default routes
 */
Route::get('/', 'HomeController@index');

/**
 * Account routes
 */
Route::get('login', 'AccountController@login');
Route::get('logout', 'AccountController@logout');
Route::get('register', 'AccountController@register');

Route::get('account/activate/{token}', array(
        'as' => 'activate',
        'uses' => 'AccountController@activate')
);

	// Login/register form post
	Route::post('login', array('uses' => 'AccountController@login'));
	Route::post('register', array('uses' => 'AccountController@register'));


/**
 * User routes
 */
Route::get('profile/{user}', 'UserController@getProfile');

	// Register user post
	Route::post('register-user', array('uses' => 'UsersController@registerUser'));

    // Update user image
    Route::post('user-img', array('uses' => 'ImageController@postUpdateUserImage'));

// Upload
	Route::post('images/add', array('uses' => 'ImageController@postAddImage'));
	Route::post('images/upload', array('uses' => 'ImageController@postUploadImages'));
	Route::post('images/removeTmpImages', array('uses' => 'ImageController@postRemoveTmpImages'));

/**
 * Public image routes
 */
Route::get('p/{albumOrImage}', 'ImageController@getPublicAlbumImage');


/*********************************************
 * Single image.
 * This is a catch all route.
 * This MUST be the bottom route.
*********************************************/
Route::get('/{image}', 'ImageController@getImage');