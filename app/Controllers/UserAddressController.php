<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Users;
use Demo\Models\UserAddress;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Pecee\Controllers\IResourceController;

class UserAddressController implements IResourceController
{
    /**
     * @var UserAddressRepositoryInterface
     */
    private $userAddressRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserAddressController constructor.
     * @param UserAddressRepositoryInterface $userAddressRepository
     */
    public function __construct(UserAddressRepositoryInterface $userAddressRepository, UserRepositoryInterface $userRepository)
    {
        $this->userAddressRepository = $userAddressRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return string|null
     */
    public function index(): ?string
    {
        return response()->json([
            'Nada'
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $addressList = $this->userAddressRepository->find($id);
        return response()->json([
            'show' => $addressList
        ]);
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return response()->json([
            'Nada'
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
            $user = $this->userRepository->whereFirst('email',xorEncrypt($request['email']));

            if(isset($user)){
                $create = [ 'user_id'=> $user->id,
                    'cep' => xorEncrypt($request['cep']),
                    'address' => xorEncrypt($request['address']),
                    'number' => xorEncrypt($request['number']),
                    'reference' => xorEncrypt($request['reference']),
                    'observation' => xorEncrypt($request['observation'])];
                $address = $this->userAddressRepository->create($create);
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

    /**
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        $addressList = $this->userAddressRepository->find($id);
        return response()->json([
            'edit' => $addressList
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        try{
            $request = input()->all();
            $update = ['cep' => xorEncrypt($request['cep']),
                'address' => xorEncrypt($request['address']),
                'number' => xorEncrypt($request['number']),
                'reference' => xorEncrypt($request['reference']),
                'observation' => xorEncrypt($request['observation'])];
            $user = $this->userAddressRepository->update($id, $update);

            return response()->json([
                'update' => "Updated address " . $id
            ]);
        } catch(\Exception $ex) {
            throwException($ex);
        }
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function destroy($id): ?string
    {
        try{
            $destroy = $this->userAddressRepository->remove($id);
            return response()->json([
                'destroy' => 'Address removed!'
            ]);
        } catch(\Exception $ex) {
            throwException($ex);
        }
    }
}
