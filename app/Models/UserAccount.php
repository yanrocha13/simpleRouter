<?php
declare(strict_types=1);

namespace Demo\Models;
use \Illuminate\Database\Eloquent\Model;


class UserAccount extends Model
{
    protected $table = 'user_account';
    protected $fillable = ['user_id',
        'account_number',
        'funds'];

    public function user()
    {
        return $this->belongsTo(Users::class,'user_id','id');
    }

    public function destinationTransaction()
    {
        return $this->hasMany(AccountTransactions::class,'account_destination_id','id');
    }

    public function originTransaction()
    {
        return $this->hasMany(AccountTransactions::class,'account_origin_id','id');
    }

    public function deposit($transaction){
        try{
            //TODO CHANGE USER ACCOUNT CATCH TO LOCAL USER ID
            $userAccount = UserAccount::where('account_number',xorEncrypt($transaction['account_destination_id']));
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
            $originAccount = UserAccount::where('account_number',xorEncrypt($transaction['account_origin_id']));
            $originFunds = xorEncrypt($originAccount->funds,'decrypt');

            $destinationAccount = UserAccount::where('account_number',xorEncrypt($transaction['account_destination_id']));
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
            $userAccount = UserAccount::where('account_number',xorEncrypt($transaction['account_destination_id']));
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
}
