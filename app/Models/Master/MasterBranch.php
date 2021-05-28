<?php

namespace App\Models\Master;

use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MasterBranch extends Model implements Auditable
{
    use HasFactory, RecordSignature;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code', 'branch', 'name', 'tax', 'fax', 'phone', 'address'
    ];
}
