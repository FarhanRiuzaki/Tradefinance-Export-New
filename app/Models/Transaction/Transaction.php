<?php

namespace App\Models\Transaction;

use App\Models\Master\MasterBranch;
use App\Models\Master\MasterCifmast;
use App\Models\Master\MasterFlag;
use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Transaction extends Model implements Auditable
{
    use HasFactory, RecordSignature;
    use \OwenIt\Auditing\Auditable;

    protected $fillable= [
        'code',
        'source',
        'lc_code',
        'amount',
        'amount_limit',
        'beneficiary',
        'sender',
        'currency',
        'cif',
        'branch_code',
        'phone',
        'npwp',
        'lc_purpose',
        'lc_type',
        'facility_type',
        'rek_payment',
        'rek_export',
        'maker_note',
        'flag_id',
        'flag_data',
        'approve_date',
        'approve_by',
        'amend_approve_date',
        'counter_revisi',
        'approve_note',
        'charges_currency_a',
        'charges_amount_a',
        'charges_currency_b',
        'charges_amount_b',
    ];

    public function mt700()
    {
        return $this->hasOne(TransactionMt700::class, 'transactions_id');
    }

    public function mt707()
    {
        return $this->hasOne(TransactionMt707::class, 'transactions_id');
    }

    public function mt710()
    {
        return $this->hasOne(TransactionMt710::class, 'transactions_id');
    }

    public function flag()
    {
        return $this->belongsTo(MasterFlag::class);
    }

    public function branchs()
    {
        return $this->belongsTo(MasterBranch::class, 'branch_code', 'code');
    }
    // public function document()
    // {
    //     return $this->hasMany(TransactionsDocument::class);
    // }

    // public function sors()
    // {
    //     return $this->hasMany(TransactionsSor::class);
    // }


    // public function flow()
    // {
    //     return $this->hasMany(TransactionsFlow::class);
    // }

    public function cifmast()
    {
        return $this->belongsTo(MasterCifmast::class, 'cif', 'CIFNO');
    }
}
