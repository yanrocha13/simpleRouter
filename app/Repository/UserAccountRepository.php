<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\UserAccount;
use Demo\Repository\Api\UserAccountInterface;
use Illuminate\Database\Eloquent\Model;

class UserAccountRepository implements UserAccountInterface
{

    public function create($userAccount){
        return UserAccount::create($userAccount);
    }

    public function createAccountNumber($user){
        $accountNumber = "";
        $accountNumber .= strval($user->id);
        $accountNumber .= strval(strlen($user->email) * $user->id);
        $accountNumber .= strval(strlen($user->name) * $user->id);

        return  $accountNumber;
    }

}
