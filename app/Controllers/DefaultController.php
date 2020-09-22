<?php
namespace Demo\Controllers;


use Demo\Repository\AuthRepository;
use Demo\Models\Users;

class DefaultController
{

    /**
     * @var AuthRepository
     */
    private $auth;

    public function __construct(
        AuthRepository $auth
    )
    {
        $this->auth = $auth;
    }

    public function authentication(){
        $user = input()->all();
        $token = $this->auth->authentication($user);
        if(isset($token)){
            return $token;
        }
        else {
            return response()->json([
                'Error' => "Ocorreu um erro durante a autenticacao e nao foi possivel gerar o seu Token, verifique os dados e tente novamente."
            ]);
        }
    }


	public function home(): string
	{
		// implement
		return response()->json([
        'Error' => $_COOKIE["authentication"]]);
	}

	public function contact(): string
	{
        return 'DefaultController -> contact';
	}

	public function companies($id = null): string
	{
        return 'DefaultController -> companies -> id: ' . $id;
	}

    public function notFound(): string
    {
        return 'Page not found';
    }

}