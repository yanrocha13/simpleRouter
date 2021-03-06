<?php

use Demo\Models\Users;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param string|array|null $parameters
 * @param array|null $getParams
 * @return \Pecee\Http\Url
 * @throws \InvalidArgumentException
 */
function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
{
    return Router::getUrl($name, $parameters, $getParams);
}

/**
 * @return \Pecee\Http\Response
 */
function response(): Response
{
    return Router::response();
}

/**
 * @return \Pecee\Http\Request
 */
function request(): Request
{
    return Router::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param string|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return \Pecee\Http\Input\InputHandler|\Pecee\Http\Input\IInputItem|string
 */
function input($index = null, $defaultValue = null, ...$methods)
{
    if ($index !== null) {
        return request()->getInputHandler()->getValue($index, $defaultValue, ...$methods);
    }

    return request()->getInputHandler();
}

/**
 * @return string|null
 */
function headers()
{
    return request()->getHeader('Authorization');
}

/**
 * @param string $url
 * @param int|null $code
 */
function redirect(string $url, ?int $code = null): void
{
    if ($code !== null) {
        response()->httpCode($code);
    }

    response()->redirect($url);
}

/**
 * Get current csrf-token
 * @return string|null
 */
function csrf_token(): ?string
{
    $baseVerifier = Router::router()->getCsrfVerifier();
    if ($baseVerifier !== null) {
        return $baseVerifier->getTokenProvider()->getToken();
    }

    return null;
}

function xorEncrypt($value, $type = "encrypt")
{
    if ($type == "decrypt") {
        $value = base64_decode($value);
    }
    $customKey       = 'simplerouter';
    $valueLength     = strlen($value);
    $customKeyLength = strlen($customKey);
    for ($i = 0; $i < $valueLength; $i++) {
        for ($j = 0; $j < $customKeyLength; $j++) {
            if ($type == "decrypt") {
                $value[$i] = $customKey[$j] ^ $value[$i];
            } else {
                $value[$i] = $value[$i] ^ $customKey[$j];
            }
        }
    }

    $result = $value;

    if ($type == "encrypt") {
        $result = base64_encode($value);
    }

    return $result;
}

function helperDecryptArray($value, $arrayDefinition, $key = 'simplerouter')
{
    $result = [];
    foreach($value as $key => $item){
        if($key == 'id'){
            $result = array_merge($result, [$key => $item]);
        }
        else if(in_array($key,$arrayDefinition)){
            $result = array_merge($result,[$key => xorEncrypt($item,'decrypt')]);
        }
    }
    return $result;
}

function getUser(){
    $user = '';
    $AUTH = xorEncrypt($_COOKIE['authentication'], "decrypt");
    $userAuth[] = explode(":",$AUTH);
    $user = Users::where('email', $userAuth[0][0])->with('account')->first();
    return $user;
}