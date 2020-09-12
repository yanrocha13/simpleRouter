<?php
declare(strict_types=1);

namespace App\models;
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
}
