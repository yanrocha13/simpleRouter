<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\UserPhone;
use Demo\Repository\Api\UserPhoneRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Pecee\Controllers\IResourceController;

class UserPhoneController implements IResourceController
{
    /**
     * @var UserRepositoryInterface
     */
    private $usersRepository;

    /**
     * @var UserPhoneRepositoryInterface
     */
    private $userPhoneRepository;

    /**
     * UsersController constructor.
     * @param UserRepositoryInterface $usersRepository
     * @param UserPhoneRepositoryInterface $userPhoneRepository
     */
    public function __construct(UserRepositoryInterface $usersRepository, UserPhoneRepositoryInterface $userPhoneRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->userPhoneRepository = $userPhoneRepository;
    }

    /**
     * @return string|null
     */
    public function index(): ?string
    {
        return "none";
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $phoneList = $this->userPhoneRepository->find($id);
        return response()->json([
            'show' => $phoneList
        ]);
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return response()->json([
            'create' => "nada"
        ]);
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        try{
            $request = input()->all();
            $user = $this->usersRepository->whereFirst('email',xorEncrypt($request['email']));

            if(isset($user)){
                $create = [ 'user_id'=> $user->id,
                    'phone' => xorEncrypt($request['phone'])];
                $phone = $this->userPhoneRepository->create($create);
                return response()->json([
                    'store' => "Phone added to user " . $user->id
                ]);
            }else {
                return response()->json([
                    'Error' => "Ocorreu um error ao salvar os dados, confira os dados ou tente novamente mais tarde."
                ]);
            }

        }catch (ExceptionInterface $ex){
            throwException($ex);
        }
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        $phoneList = $this->userPhoneRepository->find($id);
        return response()->json([
            'edit' => $phoneList
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
            $update = [ "phone"=> xorEncrypt($request['phone'])];
            $user = $this->userPhoneRepository->update($id, $update);

            return response()->json([
                'update' => "Updated phone " . $id
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
            $destroy = $this->userPhoneRepository->remove($id);
            return response()->json([
                'destroy' => 'Phone number removed!'
            ]);
        } catch(\Exception $ex) {
            throwException($ex);
        }
    }
}
