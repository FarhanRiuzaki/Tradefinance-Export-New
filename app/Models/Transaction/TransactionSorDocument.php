<?php

namespace App\Models\Transaction;

use App\Models\Master\MasterDocumentParameter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TransactionSorDocument extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'transaction_sors_id', 'code', 'note', 'file'
    ];

    public function parameter()
    {
        return $this->belongsTo(MasterDocumentParameter::class, 'code' , 'code');
    }
}
