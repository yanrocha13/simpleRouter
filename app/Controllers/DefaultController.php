<?php
namespace Demo\Controllers;


use Demo\Models\Renderer;
use Demo\Repository\AuthRepository;
use Demo\Models\Users;
use Twig\Environment;

class DefaultController
{

    /**
     * @var AuthRepository
     */
    private $auth;

    /**
     * @var Renderer
     */
    private $twig;

    public function __construct(
        AuthRepository $auth,
        Renderer $twig
    )
    {
        $this->auth = $auth;
        $this->twig = $twig;
    }

    public function authentication(){
        $user = input()->all();
        $token = $this->auth->authentication($user);
        if(isset($token)){
            return response()->json([
                'Success' => $token
            ], 200);
        }
        else {
            return response()->json([
                'Error' => "Ocorreu um erro durante a autenticacao e nao foi possivel gerar o seu Token, verifique os dados e tente novamente."
            ], 400);
        }
    }

    public function auth()
    {
        return $this->twig->render()->render('/auth/auth.html',[null]);
    }

	public function home()
	{
		return $this->twig->render()->render('/Users/Index.html',[null]);
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
        return $this->twig->render()->render('/error/404.html',[null]);
    }

    public function error(): string
    {
        return $this->twig->render()->render('/error/error.html',[null]);
    }

    public function authError(): string
    {
        return $this->twig->render()->render('/error/notLoggedIN.html',[null]);
    }

}