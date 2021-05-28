<?php

namespace App\Models\Master;

use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class MasterDocumentParameter extends Model implements ContractsAuditable
{
    use HasFactory, RecordSignature;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code','name','status'
    ];
}
