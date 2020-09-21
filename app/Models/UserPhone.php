<?php
declare(strict_types=1);

namespace Demo\Models;
use \Illuminate\Database\Eloquent\Model;


class UserPhone extends Model
{
    protected $table = 'user_phone';
    protected $fillable = ['user_id',
        'phone'];

    public function user()
    {
        return $this->belongsTo(Users::class,'user_id','id');
    }
}
