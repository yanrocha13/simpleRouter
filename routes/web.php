<?php
/**
 * This file contains all the routes for the project
 */

use Demo\Router;


$container = (new \DI\ContainerBuilder())
    ->useAutowiring(true)
    ->build();

Router::enableDependencyInjection($container);

Router::csrfVerifier(new \Demo\Middlewares\CsrfVerifier());

Router::group(['namespace' => '\Demo\Controllers', 'exceptionHandler' => \Demo\Handlers\CustomExceptionHandler::class], function () {

	Router::get('/', 'DefaultController@home')->setName('home');

	Router::get('/contact', 'DefaultController@contact')->setName('contact');

	Router::basic('/companies/{id?}', 'DefaultController@companies')->setName('companies');

    // API

	Router::group(['prefix' => '/api', 'middleware' => \Demo\Middlewares\ApiVerification::class], function () {
		Router::resource('/demo', 'ApiController');
        Router::resource('/user', 'UsersController');
        Router::resource('/phone', 'UserPhoneController');
        Router::resource('/address', 'UserAddressController');
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