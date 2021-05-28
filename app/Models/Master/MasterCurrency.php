<?php

namespace App\Models\Master;

use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MasterCurrency extends Model implements Auditable
{
    use HasFactory, RecordSignature;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code', 'description', 'bi_id', 'gl_code'
    ];
}
