<?php

namespace App\Models\Master;

use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class MasterProduct extends Model implements ContractsAuditable
{
    use HasFactory, RecordSignature, Auditable;

    protected $fillable = [
        'code', 'name'
    ];
}
