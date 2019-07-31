<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
	$router->post('register', 'User\AuthController@register');
	$router->post('login', 'User\AuthController@login');
	$router->post('refresh', 'User\AuthController@refresh');

	$router->group(['middleware' => 'auth'], function () use ($router) {
		$router->post('logout', 'User\AuthController@logout');
	});

	$router->group(['prefix' => 'posts', 'middleware' => 'auth'], function () use ($router) {
		$router->get('/', 'Post\PostController@index');
		$router->post('/', 'Post\PostController@store');
		$router->get('/{id}', 'Post\PostController@show');
		$router->delete('/{id}', 'Post\PostController@destroy');
		$router->put('/{id}', 'Post\PostController@update');
	});
});