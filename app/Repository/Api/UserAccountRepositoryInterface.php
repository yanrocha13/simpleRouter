<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

interface UserAccountRepositoryInterface
{
    public function create($userAccount);
    public function createAccountNumber($user);
}
