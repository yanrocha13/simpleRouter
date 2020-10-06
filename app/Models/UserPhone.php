<?php
declare(strict_types=1);

namespace Demo\Models;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserPhone extends Model
{
    protected $table = 'user_phone';
    protected $fillable = ['user_id',
        'phone'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Users::class,'user_id','id');
    }
}
