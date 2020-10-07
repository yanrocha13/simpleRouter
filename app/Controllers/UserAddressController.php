<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Users;
use Demo\Models\UserAddress;
use Demo\Repository\Api\LoggerRepositoryInterface;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Exception;
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
     * @var LoggerRepositoryInterface
     */
    private $loggerRepository;

    /**
     * UserAddressController constructor.
     * @param UserAddressRepositoryInterface $userAddressRepository
     * @param UserRepositoryInterface $userRepository
     * @param LoggerRepositoryInterface $loggerRepository
     */
    public function __construct(UserAddressRepositoryInterface $userAddressRepository,
                                UserRepositoryInterface $userRepository,
                                LoggerRepositoryInterface $loggerRepository)
    {
        $this->userAddressRepository = $userAddressRepository;
        $this->userRepository = $userRepository;
        $this->loggerRepository = $loggerRepository;
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

                $message = "Created address[". $address->id ."] for user " .$user->id;
                $this->loggerRepository->createModelLog("userAddress",$message,200);
                return response()->json([
                    'Success' => "Address added to user " . $user->id
                ]);
            }else {
                $message = "An error occurred while saving address to user " . $user->id;
                $this->loggerRepository->createModelLog("userAddress",$message,400);
                return response()->json([
                    'Error' => "Ocorreu um error ao salvar os dados, confira os dados ou tente novamente mais tarde."
                ]);
            }

        }catch (Exception $ex){
            $message = "An erro ocurred. More info =>" . $ex->getMessage();
            $this->loggerRepository->createModelLog("userAddress",$message,400);
            throwException($ex);
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

            $message = "Updated address[" . $user->id . "]";
            $this->loggerRepository->createModelLog("accountTransaction",$message,200);
            return response()->json([
                'update' => "Updated address " . $id
            ]);
        } catch(Exception $ex) {
            $message = "An error ocurred. More info =>" . $ex->getMessage();
            $this->loggerRepository->createModelLog("accountTransaction",$message,400);
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
        } catch(Exception $ex) {
            throwException($ex);
        }
    }
}
