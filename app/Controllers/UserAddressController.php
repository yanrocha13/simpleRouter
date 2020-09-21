<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Users;
use Demo\Models\UserAddress;
use Pecee\Controllers\IResourceController;

class UserAddressController implements IResourceController
{

    public function index(): ?string
    {
        // TODO: Implement index() method.
    }

    public function show($id): ?string
    {
        // TODO: Implement show() method.
    }

    public function store(): ?string
    {
        try{
            //TODO PASS LOCAL USER_ID IN PLACE OF USER EMAIL.
            $request = input()->all();
            $user = Users::where('email',xorEncrypt($request['email']))->first();

            if(isset($user)){
                $create = [ 'user_id'=> $user->id,
                    'cep' => xorEncrypt($request['cep']),
                    'address' => xorEncrypt($request['address']),
                    'number' => xorEncrypt($request['number']),
                    'reference' => xorEncrypt($request['reference']),
                    'observation' => xorEncrypt($request['observation'])];
                $address = UserAddress::create($create);
                return response()->json([
                    'Success' => "Address added to user " . $user->id
                ]);
            }else {
                return response()->json([
                    'Error' => "Ocorreu um error ao salvar os dados, confira os dados ou tente novamente mais tarde."
                ]);
            }

        }catch (ExceptionInterface $ex){
            return response()->json([
                'method' => $ex
            ]);
        }
    }

    public function create(): ?string
    {
        // TODO: Implement create() method.
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
