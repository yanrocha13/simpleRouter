<?php
declare(strict_types=1);

namespace Demo\Models;
use DateTime;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountTransactions extends Model
{
    protected $table = 'account_transactions';
    protected $fillable = ['account_origin_id',
        'account_destination_id',
        'value',
        'transaction_type',
        'transaction_date'];

    /**
     * @return BelongsTo
     */
    public function destinationAccount()
    {
        return $this->belongsTo(UserAccount::class,'account_destination_id','id');
    }

    /**
     * @return BelongsTo
     */
    public function originAccount()
    {
        return $this->belongsTo(UserAccount::class,'account_origin_id','id');
    }
}
