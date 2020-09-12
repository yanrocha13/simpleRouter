<?php
declare(strict_types=1);

namespace App\models;
use \Illuminate\Database\Eloquent\Model;


class UserAddress extends Model
{
    protected $table = 'user_address';
    protected $fillable = ['user_id',
        'cep',
        'address',
        'number',
        'reference',
        'observation'];

    public function user()
    {
        return $this->belongsTo(Users::class,'user_id','id');
    }
}
