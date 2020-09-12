<?php
declare(strict_types=1);

namespace Demo\Models;

use \Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['email',
                            'password',
                            'name',
                            'identification_flag',
                            'identification',
                            'registration_flag',
                            'registration',
                            'birth_date'];

    public function phones()
    {
        return $this->hasMany(UserPhone::class,'user_id','id');
    }
    public function address()
    {
        return $this->hasMany(UserAddress::class,'user_id','id');
    }
    public function account()
    {
        return $this->hasOne(UserAccount::class,'user_id','id');
    }
}
