<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

interface AccountTransactionRepositoryInterface
{
    public function whereFirst($parameter, $data);

    public function create($user);

    public function list();

    public function find($id);

    public function update($id, $update);

    public function remove($id);
}
