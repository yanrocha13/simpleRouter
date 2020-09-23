<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Repository\Api\UserAccountInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Pecee\Controllers\IResourceController;
use Symfony\Component\Translation\Exception\ExceptionInterface;

class UsersController implements IResourceController
{
    /**
     * @var UserRepositoryInterface
     */
    private $usersRepository;

    /**
     * @var UserAccountInterface
     */
    private $userAccountRepository;

    /**
     * UsersController constructor.
     * @param UserRepositoryInterface $usersRepository
     * @param UserAccountInterface $userAccountRepository
     */
    public function __construct(UserRepositoryInterface $usersRepository, UserAccountInterface $userAccountRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->userAccountRepository = $userAccountRepository;
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
            $user = $this->usersRepository->create($create);

            $account_number = $this->userAccountRepository->createAccountNumber($user);
            $newAccount = [
                "user_id" => $user->id,
                "account_number" => xorEncrypt($account_number),
                "funds" => xorEncrypt("0.00")
            ];

            $account = $this->userAccountRepository->create($newAccount);

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
        $userList = $this->usersRepository->list();
        return response()->json($userList);
    }

    public function show($id): ?string
    {
        $user = $this->usersRepository->find($id);
        return response()->json($user);
    }

    public function edit($id): ?string
    {
        $user = $this->usersRepository->find($id);
        return response()->json($user);
    }

    public function update($id): ?string
    {
        try{
            $request = input()->all();
            $update = [ "email"=> xorEncrypt($request['email']),
                "password"=> xorEncrypt($request['password']),
                "name"=> xorEncrypt($request['name']),
                "identification"=> xorEncrypt($request['identification']),
                "registration"=> xorEncrypt($request['registration']),
                "birth_date"=> xorEncrypt($request['birth_date'])];
            $user = $this->usersRepository->update($id, $update);

            return response()->json([
                'Success' => "Updated user " . $id
            ]);

        }catch(\Exception $ex){
            throwException($ex);
        }

    }

    public function destroy($id): ?string
    {
        // TODO: Implement destroy() method.
    }
}
