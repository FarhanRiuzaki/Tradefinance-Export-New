<?php

namespace App\Models\Transaction;

use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TransactionMt710 extends Model implements Auditable
{
    use HasFactory, RecordSignature;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        "F20",
        "F31C",
        "F31D",
        "F50",
        "F59",
        "F32B",
        "F39A",
        "F42A",
        "F32B_CUR",
        "F32B_AMT",
        "F42C",
        "F44C",
        "F52A",
        "filename",
        "sender",
        "receiver"
    ];
}
