<?php

use Demo\Repository\AccountTransactionRepository;
use Demo\Repository\Api\AccountTransactionInterface;
use Demo\Repository\Api\AuthInterface;
use Demo\Repository\Api\UserAccountInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Demo\Repository\AuthRepository;
use Demo\Repository\UserAccountRepository;
use Demo\Repository\UsersRepository;

return [
    UserRepositoryInterface::class => DI\autowire(UsersRepository::class),
    UserAccountInterface::class => DI\autowire(UserAccountRepository::class),
    AccountTransactionInterface::class => DI\autowire(AccountTransactionRepository::class),
    AuthInterface::class => DI\autowire(AuthRepository::class)
];