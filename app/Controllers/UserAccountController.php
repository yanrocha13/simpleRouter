<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Repository\Api\UserAccountRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Pecee\Controllers\IResourceController;

class UserAccountController implements IResourceController
{
    /**
     * @var UserRepositoryInterface
     */
    private $usersRepository;

    /**
     * @var UserAccountRepositoryInterface
     */
    private $userAccountRepository;

    /**
     * UserAccountController constructor.
     * @param UserRepositoryInterface $usersRepository
     * @param UserAccountRepositoryInterface $userAccountRepository
     */
    public function __construct(UserRepositoryInterface $usersRepository, UserAccountRepositoryInterface $userAccountRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->userAccountRepository = $userAccountRepository;
    }

    public function index(): ?string
    {
        // TODO: Implement index() method.
    }

    public function show($id): ?string
    {
        $userAccount = $this->userAccountRepository->find($id);
        return response()->json([
            'show' => $userAccount
        ]);
    }

    public function create(): ?string
    {
        // TODO: Implement create() method.
    }

    public function store(): ?string
    {
        // TODO: Implement store() method.
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
