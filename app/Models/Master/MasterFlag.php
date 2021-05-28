<?php

namespace App\Models\Master;

use App\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MasterFlag extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use RecordSignature;

    protected $fillable = [
        'parent', 'level', 'sequence', 'description'
    ];
}
