<?php

use Demo\Models\Renderer;
use Demo\Repository\AccountTransactionRepository;
use Demo\Repository\Api\AccountTransactionRepositoryInterface;
use Demo\Repository\Api\AuthInterface;
use Demo\Repository\Api\LoggerRepositoryInterface;
use Demo\Repository\Api\UserAccountRepositoryInterface;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Demo\Repository\Api\UserPhoneRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Demo\Repository\AuthRepository;
use Demo\Repository\LoggerRepository;
use Demo\Repository\UserAccountRepository;
use Demo\Repository\UserAddressRepository;
use Demo\Repository\UserPhoneRepository;
use Demo\Repository\UsersRepository;
use Monolog\Logger;
use Twig\Environment;

return [
    UserRepositoryInterface::class => DI\autowire(UsersRepository::class),
    UserAccountRepositoryInterface::class => DI\autowire(UserAccountRepository::class),
    UserPhoneRepositoryInterface::class => DI\autowire(UserPhoneRepository::class),
    UserAddressRepositoryInterface::class => DI\autowire(UserAddressRepository::class),
    AccountTransactionRepositoryInterface::class => DI\autowire(AccountTransactionRepository::class),
    AuthInterface::class => DI\autowire(AuthRepository::class),
    LoggerRepositoryInterface::class => DI\autowire(LoggerRepository::class),
    Environment::class => DI\autowire(Renderer::class),
];