<?php
declare(strict_types=1);

namespace Demo\Models;
use DateTime;
use \Illuminate\Database\Eloquent\Model;


class AccountTransactions extends Model
{
    protected $table = 'account_transactions';
    protected $fillable = ['account_origin_id',
        'account_destination_id',
        'value',
        'transaction_type',
        'transaction_date'];

    /**
     * @var UserAccount
     */
    private $userAccount;

    /**
     * AccountTransactions constructor.
     * @param array $attributes
     * @param UserAccount $userAccount
     */
    public function __construct(array $attributes = [],UserAccount $userAccount)
    {
        parent::__construct($attributes);
        $this->userAccount = $userAccount;
    }

    public function destinationAccount()
    {
        return $this->hasMany(UserAccount::class,'account_destination_id','id');
    }

    public function originAccount()
    {
        return $this->hasMany(UserAccount::class,'account_destination_id','id');
    }

    public function defineTransaction($request){
        $transaction = [];
        //TODO MODIFY ACCOUNT ORIGIN/DESTINATION TO CATCH WITH LOCAL USER WHEN TYPE 1/3 WHEN TYPE EQUAL 2 CATCH ACCOUNT NUMBER FROM REQUEST

        $user = Users::where('email',xorEncrypt($request['user_email']))->with('account')->first();
        $date = new DateTime();

        switch ($request["type"]){
            case 1:
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $user->account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('1'),
                    'transaction_date' => xorEncrypt($date)];
                break;
            case 2:
                $destination_account = UserAccount::where('user_account',$request['destination_account']);
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $destination_account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('2'),
                    'transaction_date' => xorEncrypt($date)];
                break;
            case 3:
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $user->account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('3'),
                    'transaction_date' => xorEncrypt($date)];
                break;
        }

        return $transaction;
    }

    public function executeTransaction($transaction){
        $result = false;
        switch( $transaction["type"]){
            case 1:
                $result = $this->userAccount->deposit($transaction);
                break;
            case 2:
                $result = $this->userAccount->transfer($transaction);
                break;
            case 3:
                $result = $this->userAccount->withdraw($transaction);
                break;
        }

        return $result;
    }
}
