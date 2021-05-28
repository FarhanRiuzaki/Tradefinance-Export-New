<?php

namespace App\Models\Transaction;

use App\Models\Master\MasterFlag;
use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TransactionSor extends Model implements Auditable
{
    use HasFactory, RecordSignature;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'transactions_id', 'amount', 'currency', 'code', 'flag_id'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }

    public function document()
    {
        return $this->hasMany(TransactionSorDocument::class, 'transaction_sors_id');
    }

    public function flag()
    {
        return $this->belongsTo(MasterFlag::class);
    }
}
