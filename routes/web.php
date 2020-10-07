<?php
/**
 * This file contains all the routes for the project
 */

use Demo\Router;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;


$container = (new \DI\ContainerBuilder())
    ->addDefinitions('../etc/di.php')
    ->useAutowiring(true)
    ->build();

Router::enableDependencyInjection($container);

Router::csrfVerifier(new \Demo\Middlewares\CsrfVerifier());

Router::group(['namespace' => '\Demo\Controllers', 'exceptionHandler' => \Demo\Handlers\CustomExceptionHandler::class], function () {
    Router::group(['prefix' => '/view'], function () {
        Router::get('/auth', 'DefaultController@auth');
        Router::group(['prefix' => '/show', 'middleware' => \Demo\Middlewares\ApiVerification::class], function () {
            Router::get('/transaction', 'AccountTransactionsController@viewIndex');
            Router::get('/user', 'UsersController@renderShow');
            Router::get('/user_account', 'DefaultController@home');
        });
        Router::group(['prefix' => '/create'], function () {
            Router::get('/user', 'UsersController@renderCreate');
            Router::get('/transaction', 'AccountTransactionsController@create');
        });
        Router::group(['prefix' => '/edit'], function () {
            Router::get('/user', 'DefaultController@home');
        });
        Router::group(['prefix' => '/delete'], function () {

        });
    });
    // API
	Router::group(['prefix' => '/api'], function () {
        Router::get('/', 'DefaultController@home')->setName('home');
		Router::resource('/demo', 'ApiController');
        Router::get('/user/create', 'UsersController@create');
        Router::post('/user/create', 'UsersController@store');
        Router::post('/auth', 'DefaultController@authentication');

		Router::group(['prefix' => '/v1','exceptionHandler' => \Demo\Handlers\CustomExceptionHandler::class , 'middleware' => \Demo\Middlewares\ApiVerification::class], function(){
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

            /** ADDRESS */
            Router::get('/address/{id}', 'UserAddressController@show');
            Router::get('/address/edit/{id}', 'UserAddressController@edit');
            Router::post('/address/edit/{id}', 'UserAddressController@update');
            Router::post('/address/create', 'UserAddressController@store');
            Router::delete('/address/delete/{id}', 'UserAddressController@destroy');

            /** USERACCOUNT */
            Router::get('/account/{id}', 'UserAccountController@show');

            /** ACCOUNTTRANSACTION */
            Router::get('/transaction/', 'AccountTransactionsController@index');
            Router::get('/transaction/{id}', 'AccountTransactionsController@show');
            Router::get('/transaction/create', 'AccountTransactionsController@create');
            Router::post('/transaction/create', 'AccountTransactionsController@store');

        });
	});
});

Router::group(['namespace' => '\Demo\Controllers', 'prefix' => '/error','exceptionHandler' => \Demo\Handlers\CustomExceptionHandler::class], function () {

    Router::get('/not-found', 'DefaultController@notFound');
    Router::get('/', 'DefaultController@error');
    Router::get('/auth', 'DefaultController@authError')->name('authError');

});

Router::error(function(Request $request, Exception $exception) {
    if($exception instanceof NotFoundHttpException && $exception->getCode() === 404) {
        response()->redirect('/error/not-found');
    }
});