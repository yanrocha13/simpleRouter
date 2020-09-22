<?php
namespace Demo\Middlewares;

use Demo\Models\Users;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class ApiVerification implements IMiddleware
{
    public function handle(Request $request) : void
    {
        // Do authentication
        try{
            $authCookie = $_COOKIE['authentication'];
            if(isset($authCookie)){
                $autentication = xorEncrypt($authCookie,'decrypt');
                $userData = explode(':',$autentication);

                $user = Users::where("email", $userData[0])->first();
                if(isset($user) && $user->password == $userData[1]){
                    $request->authenticated = true;
                }
                else{
                    $request->authenticated = false;
                    $request->setRewriteUrl(url('/teste'));
                }
            }else{
                $request->authenticated = false;
                $request->setRewriteUrl(url('/teste'));
            }
        }catch (\Exception $ex){
            throwException($ex);
        }
    }

}