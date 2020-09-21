<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\UserPhone;
use Demo\Models\Users;
use Pecee\Controllers\IResourceController;

class UserPhoneController implements IResourceController
{

    /**
     * @return string|null
     */
    public function index(): ?string
    {
        return response()->json([
            'method' => "nada"
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        return response()->json([
            'method' => "nada"
        ]);
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        try{
            //TODO PASS LOCAL USER_ID IN PLACE OF USER EMAIL.
            $request = input()->all();
            $user = Users::where('email',xorEncrypt($request['email']))->first();

            if(isset($user)){
                $create = [ 'user_id'=> $user->id,
                    'phone' => xorEncrypt($request['phone'])];
                $phone = UserPhone::create($create);
                return response()->json([
                    'Success' => "Phone added to user " . $user->id
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

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return response()->json([
            'method' => "nada"
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        return response()->json([
            'method' => "nada"
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        return response()->json([
            'method' => "nada"
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function destroy($id): ?string
    {
        return response()->json([
            'method' => "nada"
        ]);
    }
}
