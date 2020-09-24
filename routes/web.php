<?php
/**
 * This file contains all the routes for the project
 */

use Demo\Router;


$container = (new \DI\ContainerBuilder())
    ->addDefinitions('../etc/di.php')
    ->useAutowiring(true)
    ->build();

Router::enableDependencyInjection($container);

Router::csrfVerifier(new \Demo\Middlewares\CsrfVerifier());

Router::group(['namespace' => '\Demo\Controllers', 'exceptionHandler' => \Demo\Handlers\CustomExceptionHandler::class], function () {

	Router::get('/', 'DefaultController@home')->setName('home');

	Router::get('/contact', 'DefaultController@contact')->setName('contact');

	Router::basic('/companies/{id?}', 'DefaultController@companies')->setName('companies');

    // API

	Router::group(['prefix' => '/api'], function () {
        Router::get('/', 'DefaultController@home')->setName('home');
		Router::resource('/demo', 'ApiController');
        Router::get('/user/create', 'UsersController@create');
        Router::post('/user/create', 'UsersController@store');

		Router::group(['prefix' => '/v1','exceptionHandler' => \Core\Handlers\CustomExceptionHandler::class , 'middleware' => \Demo\Middlewares\ApiVerification::class], function(){
		    /** USER */
            Router::get('/user/', 'UsersController@index');
            Router::get('/user/{id}', 'UsersController@show');
            Router::get('/user/edit/{id}', 'UsersController@edit');
            Router::post('/user/edit/{id}', 'UsersController@update');

            /** PHONE */
            Router::get('/phone/{id}', 'UserPhoneController@show');
            Router::get('/phone/edit/{id}', 'UserPhoneController@edit');
            Router::post('/phone/edit/{id}', 'UserPhoneController@update');
            Router::post('/phone/create', 'UserPhoneController@store');
            Router::delete('/phone/delete/{id}', 'UserPhoneController@destroy');

            /**  */
            Router::resource('/address', 'UserAddressController');
        });
        Router::post('/auth', 'DefaultController@authentication');
	});


    // CALLBACK EXAMPLES

    Router::get('/foo', function() {
        return 'foo';
    });

    Router::get('/foo-bar', function() {
        return 'foo-bar';
    });

});