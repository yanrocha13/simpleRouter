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

    /**
     * @return string|null
     */
    public function index(): ?string
    {
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $userAccount = $this->userAccountRepository->find($id);
        return response()->json([
            'show' => $userAccount
        ]);
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function destroy($id): ?string
    {
        return null;
    }
}
