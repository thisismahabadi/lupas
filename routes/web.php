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
	$router->group(['namespace' => 'User'], function () use ($router) {
		$router->post('register', 'AuthController@register');
		$router->post('login', 'AuthController@login');
		$router->post('refresh', 'AuthController@refresh');
	});

	$router->group(['middleware' => 'auth:api', 'namespace' => 'User'], function () use ($router) {
		$router->post('logout', 'AuthController@logout');
	});

	$router->group(['prefix' => 'posts', 'middleware' => 'auth:api', 'namespace' => 'Post'], function () use ($router) {
		$router->get('/', 'PostController@index');
		$router->post('/', 'PostController@store');
		$router->get('/{id}', 'PostController@show');
		$router->delete('/{id}', 'PostController@destroy');
		$router->put('/{id}', 'PostController@update');
	});
});