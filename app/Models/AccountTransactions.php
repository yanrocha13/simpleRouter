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

    public function destinationAccount()
    {
        return $this->hasMany(UserAccount::class,'account_destination_id','id');
    }

    public function originAccount()
    {
        return $this->hasMany(UserAccount::class,'account_destination_id','id');
    }
}
