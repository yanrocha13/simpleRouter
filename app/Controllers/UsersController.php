<?php
declare(strict_types=1);

namespace Demo\Controllers;


use Demo\Models\UserAccount;
use Demo\Models\Users;
use Pecee\Controllers\IResourceController;
use Pecee\Http\Request;
use Symfony\Component\Translation\Exception\ExceptionInterface;
use Twig\Environment;
use Illuminate\Support\Facades\Crypt;

class UsersController implements IResourceController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->twig = require(__DIR__ . '/../../renderer.php');
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return response()->json([
            'method' => input()->all()
        ]);
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        try{
            $request = input()->all();
            $create = [ "email"=> xorEncrypt($request['email']),
                        "password"=> xorEncrypt($request['password']),
                        "name"=> xorEncrypt($request['name']),
                        "identification"=> xorEncrypt($request['identification']),
                        "registration"=> xorEncrypt($request['registration']),
                        "birth_date"=> xorEncrypt($request['birth_date'])];
            $user = Users::create($create);


            $account_number = "" . strval($user->id) . strval(strlen($user->email) * $user->id) . strval(strlen($user->name) * $user->id);
            $newAccount = [
                "user_id" => $user->id,
                "account_number" => xorEncrypt($account_number),
                "funds" => xorEncrypt("0.00")
            ];

            $account = UserAccount::create($newAccount);

            return response()->json([
                'Success' => "Created user " . $user->id . ". With account number: " . xorEncrypt($account->account_number,'decrypt')
            ]);
        }catch (ExceptionInterface $ex){
            return response()->json([
                'method' => $ex
            ]);
        }
    }

    public function index(): ?string
    {
        // TODO: Implement show() method.
    }

    public function show($id): ?string
    {
        // TODO: Implement show() method.
    }

    public function edit($id): ?string
    {
        // TODO: Implement edit() method.
    }

    public function update($id): ?string
    {
        // TODO: Implement update() method.
    }

    public function destroy($id): ?string
    {
        // TODO: Implement destroy() method.
    }
}
