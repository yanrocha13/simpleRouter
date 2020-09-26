<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\UserAccount;
use Demo\Repository\Api\UserAccountRepositoryInterface;

class UserAccountRepository implements UserAccountRepositoryInterface
{
    /**
     * @var UserAccount
     */
    private $userAccount;

    /**
     * UserAccountRepository constructor.
     * @param UserAccount $userAccount
     */
    public function __construct(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function create($userAccount){
        return $this->userAccount->create($userAccount);
    }

    public function createAccountNumber($user){
        $accountNumber = "";
        $accountNumber .= strval($user->id);
        $accountNumber .= strval(strlen($user->email) * $user->id);
        $accountNumber .= strval(strlen($user->name) * $user->id);

        return  $accountNumber;
    }

    public function remove($id){
        return $this->userAccount->find($id)->delete();
    }

    public function find($id)
    {
        return $this->userAccount->find($id);
    }
}
