<?php
declare(strict_types=1);

namespace Demo\Models;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserAddress extends Model
{
    protected $table = 'user_address';
    protected $fillable = ['user_id',
        'cep',
        'address',
        'number',
        'reference',
        'observation'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Users::class,'user_id','id');
    }
}
