<?php
declare(strict_types=1);

namespace Demo\Models;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['email',
                            'password',
                            'name',
                            'identification',
                            'registration',
                            'birth_date'];

    /**
     * @return HasMany
     */
    public function phones()
    {
        return $this->hasMany(UserPhone::class,'user_id','id');
    }

    /**
     * @return HasMany
     */
    public function address()
    {
        return $this->hasMany(UserAddress::class,'user_id','id');
    }

    /**
     * @return HasOne
     */
    public function account()
    {
        return $this->hasOne(UserAccount::class,'user_id','id');
    }
}
