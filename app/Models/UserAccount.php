<?php
declare(strict_types=1);

namespace Demo\Models;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class UserAccount extends Model
{
    protected $table = 'user_account';
    protected $fillable = ['user_id',
        'account_number',
        'funds'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Users::class,'user_id','id');
    }

    /**
     * @return HasMany
     */
    public function destinationTransaction()
    {
        return $this->hasMany(AccountTransactions::class,'account_destination_id','id');
    }

    /**
     * @return HasMany
     */
    public function originTransaction()
    {
        return $this->hasMany(AccountTransactions::class,'account_origin_id','id');
    }
}
