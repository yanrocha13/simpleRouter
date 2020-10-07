<?php
namespace Demo\Middlewares;

use Demo\Models\Users;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class ApiVerification implements IMiddleware
{
    /**
     * @param Request $request
     * @throws \Exception
     */
    public function handle(Request $request) : void
    {
        // Do authentication
        try{
            if(isset($_COOKIE['authentication'])){
                $authCookie = $_COOKIE['authentication'];
                $autentication = xorEncrypt($authCookie,'decrypt');
                $userData = explode(':',$autentication);

                $user = Users::where("email", $userData[0])->first();
                if(isset($user) && $user->password == $userData[1]){
                    $request->authenticated = true;
                }
                else{
                    $request->authenticated = false;
                    $request->setRewriteUrl(url('/error/auth'));
                }
            }else{
                $request->authenticated = false;
                $request->setRewriteUrl(url('/error/auth'));
            }
        }catch (\Exception $ex){
              throw new \Exception('$ex');
        }
    }

}