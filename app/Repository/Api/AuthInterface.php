<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

use Demo\Models\Users;

interface AuthInterface
{
    public function authentication($user);
    public function makeToken(Users $users);
}
