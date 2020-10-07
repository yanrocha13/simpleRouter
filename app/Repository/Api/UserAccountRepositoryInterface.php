<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

interface UserAccountRepositoryInterface
{
    public function create($userAccount);
    public function createAccountNumber($user);
    public function find($id);
    public function remove($id);
    public function withdraw($transaction): bool;
    public function transfer($transaction): bool;
    public function deposit($transaction): bool;
    public function whereFirst($parameter, $data);
    public function getUserAccountDecrypted($parameter, $data);
}
