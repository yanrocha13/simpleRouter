<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\UserAccount;
use Demo\Repository\Api\UserAccountRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;

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

    /**
     * @param $userAccount
     * @return mixed
     */
    public function create($userAccount){
        return $this->userAccount->create($userAccount);
    }

    /**
     * @param $user
     * @return string
     */
    public function createAccountNumber($user){
        $accountNumber = "";
        $accountNumber .= strval($user->id);
        $accountNumber .= strval(strlen($user->email) * $user->id);
        $accountNumber .= strval(strlen($user->name) * $user->id);

        return  $accountNumber;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id){
        return $this->userAccount->find($id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->userAccount->find($id);
    }

    /**
     * @param $parameter
     * @param $data
     * @return mixed
     */
    public function whereFirst($parameter, $data){
        return $this->userAccount->where($parameter,$data)->first();
    }

    /**
     * @param $transaction
     * @return bool
     */
    public function deposit($transaction): bool
    {
        try{
            $user = getUser();
            $userAccount = $this->whereFirst('user_id',$user->id);
            $funds = xorEncrypt($userAccount->funds,'decrypt');

            if($funds == "0.00"){
                $funds = $transaction["value"];
            }else{
                $newFunds = (int)xorEncrypt($transaction["value"],"decrypt") + (int)$funds;
                $funds = xorEncrypt(strval($newFunds));
            }

            $userAccount->funds = $funds;
            $userAccount->save();
            return true;

        }catch(\Exception $ex){
            return false;
        }
    }

    /**
     * @param $transaction
     * @return bool
     */
    public function transfer($transaction): bool
    {
        try{
            $user = getUser();
            $originAccount = $this->whereFirst('user_id',$user->id);
            $originFunds = xorEncrypt($originAccount->funds,'decrypt');

            $destinationAccount = $this->whereFirst('id',$transaction['account_destination_id']);
            $destinationFunds = xorEncrypt($destinationAccount->funds,'decrypt');

            $transaction["value"] = xorEncrypt($transaction["value"], "decrypt");

            if($originFunds == "0.00" || (int)$originFunds - (int)$transaction["value"] <= 0){
                return false;
            }else{
                $destinationFunds = $destinationFunds + $transaction["value"];
                $originFunds = $originFunds - $transaction["value"];
            }

            $originAccount->funds = xorEncrypt(strval($originFunds));
            $destinationAccount->funds = xorEncrypt(strval($destinationFunds));

            $originAccount->save();
            $destinationAccount->save();

            return true;

        }catch(\Exception $ex){
            return false;
        }
    }

    /**
     * @param $transaction
     * @return bool
     */
    public function withdraw($transaction): bool
    {
        try{
            $user = getUser();
            $userAccount = $this->whereFirst('user_id',$user->id);
            $funds = xorEncrypt($user->account->funds,'decrypt');
            $transaction["value"] = xorEncrypt($transaction["value"], "decrypt");

            if($funds == "0.00" || (int)$funds - (int)$transaction["value"] <= 0){
                return false;
            }else{
                $funds = (int)$funds - (int)$transaction["value"];
            }

            $userAccount->funds = xorEncrypt(strval($funds));
            $userAccount->save();
            return true;

        }catch(\Exception $ex){
            return false;
        }
    }

    /**
     * @param $parameter
     * @param $data
     * @return array
     */
    public function getUserAccountDecrypted($parameter, $data)
    {
        $userAccount = $this->whereFirst('user_id',getUser()->id);

        $decrypt = ['account_number',
            'funds'];

        return helperDecryptArray($userAccount->getAttributes(), $decrypt);
    }
}
