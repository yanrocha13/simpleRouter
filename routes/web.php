<?php
/**
 * This file contains all the routes for the project
 */

use Demo\Handlers\CustomExceptionHandler;
use Demo\Middlewares\ApiVerification;
use Demo\Middlewares\CsrfVerifier;
use Demo\Middlewares\LoggerData;
use Demo\Middlewares\LoggerView;
use Demo\Router;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;


$container = (new \DI\ContainerBuilder())
    ->addDefinitions('../etc/di.php')
    ->useAutowiring(true)
    ->build();

Router::enableDependencyInjection($container);

Router::csrfVerifier(new CsrfVerifier());

Router::group(['namespace' => '\Demo\Controllers', 'exceptionHandler' => CustomExceptionHandler::class], function () {
    // VIEWS

    Router::get('/view/auth', 'DefaultController@auth');
    Router::get('/view/create/user', 'UsersController@renderCreate');
    Router::group(['prefix' => '/view', 'middleware' => LoggerView::class], function () {
        Router::group(['prefix' => '/show', 'middleware' => ApiVerification::class], function () {
            Router::get('/transaction', 'AccountTransactionsController@viewIndex');
            Router::get('/user', 'UsersController@renderShow');
            Router::get('/user_account', 'DefaultController@home');
        });
        Router::group(['prefix' => '/create'], function () {
            Router::get('/transaction', 'AccountTransactionsController@create');
        });
        Router::group(['prefix' => '/edit'], function () {
            Router::get('/user', 'DefaultController@home');
        });
        Router::group(['prefix' => '/delete'], function () {

        });
    });
    // API

    Router::post('/api/auth', 'DefaultController@authentication');
    Router::post('/api/user/create', 'UsersController@store');
	Router::group(['prefix' => '/api', 'middleware' => LoggerData::class], function () {
        Router::get('/', 'DefaultController@home')->setName('home');
		Router::resource('/demo', 'ApiController');
        Router::get('/user/create', 'UsersController@create');

		Router::group(['prefix' => '/v1','exceptionHandler' => CustomExceptionHandler::class , 'middleware' => ApiVerification::class], function(){
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

/** ERRORS */
Router::group(['namespace' => '\Demo\Controllers', 'prefix' => '/error','exceptionHandler' => CustomExceptionHandler::class], function () {

    Router::get('/not-found', 'DefaultController@notFound');
    Router::get('/', 'DefaultController@error');
    Router::get('/auth', 'DefaultController@authError')->name('authError');

});

Router::error(function(Request $request, Exception $exception) {
    if($exception instanceof NotFoundHttpException && $exception->getCode() === 404) {
        response()->redirect('/error/not-found');
    }
});