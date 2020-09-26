<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

interface UserRepositoryInterface
{
    public function whereFirst($parameter, $data);

    public function create($user);

    public function list();

    public function find($id);

    public function update($id, $update);

    public function remove($id);
    public function whereFirstWithRelation($parameter, $data, $relation = null);
}
