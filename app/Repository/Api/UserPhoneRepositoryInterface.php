<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

interface UserPhoneRepositoryInterface
{
    public function whereFirst($parameter, $data);

    public function create($userPhone);

    public function list();

    public function find($id);

    public function update($id, $update);

    public function remove($id);
}
