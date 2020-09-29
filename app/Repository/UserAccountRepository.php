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

    public function whereFirst($parameter, $data){
        return $this->userAccount->where($parameter,$data)->first();
    }

    public function deposit($transaction){
        try{
            //TODO CHANGE USER ACCOUNT CATCH TO LOCAL USER ID
            $userAccount = $this->whereFirst('account_number',xorEncrypt($transaction['account_destination_id']));
            $funds = xorEncrypt($userAccount->funds,'decrypt');

            if($funds == "0.00"){
                $funds = $transaction["value"];
            }else{
                $funds = $transaction["value"] + $funds;
            }

            $userAccount->funds = xorEncrypt($funds);
            $userAccount->save();
            return true;

        }catch(\Exception $ex){
            return false;
        }
    }

    public function transfer($transaction){
        try{
            //TODO CHANGE USER ACCOUNT CATCH TO LOCAL USER ID
            $originAccount = $this->whereFirst('account_number',xorEncrypt($transaction['account_origin_id']));
            $originFunds = xorEncrypt($originAccount->funds,'decrypt');

            $destinationAccount = $this->whereFirst('account_number',xorEncrypt($transaction['account_destination_id']));
            $destinationFunds = xorEncrypt($destinationAccount->funds,'decrypt');

            if($originFunds == "0.00" || $originFunds - $transaction["value"] == 0){
                return false;
            }else{
                $destinationFunds = $destinationFunds + $transaction["value"];
                $originFunds = $originFunds - $transaction["value"];
            }

            $originAccount->funds = xorEncrypt($originFunds);
            $destinationAccount->funds = xorEncrypt($destinationFunds);

            $originAccount->save();
            $destinationAccount->save();

            return true;

        }catch(\Exception $ex){
            return false;
        }
    }

    public function withdraw($transaction){
        try{
            //TODO CHANGE USER ACCOUNT CATCH TO LOCAL USER ID
            $userAccount = $this->whereFirst('account_number',xorEncrypt($transaction['account_destination_id']));
            $funds = xorEncrypt($userAccount->funds,'decrypt');

            if($funds == "0.00" || $funds - $transaction["value"] == 0){
                return false;
            }else{
                $funds = $funds - $transaction["value"];
            }

            $userAccount->funds = xorEncrypt($funds);
            $userAccount->save();
            return true;

        }catch(\Exception $ex){
            return false;
        }
    }

    public function getUserAccountDecrypted($parameter, $data)
    {
        $userAccount = $this->whereFirst('user_id',5);

        $decrypt = ['account_number',
            'funds'];

        return helperDecryptArray($userAccount->getAttributes(), $decrypt);
    }
}
