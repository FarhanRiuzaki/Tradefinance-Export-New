<?php

namespace App\Models\MT;

use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mt700 extends Model
{
    use HasFactory;

    protected $connection   = 'mt_connection';
    protected $table        = 'mt700';

    public function mt701()
    {
        return $this->HasOne(Mt701::class, 'F20', 'F20');
    }

    public function mt707()
    {
        return $this->HasOne(Mt707::class, 'F20', 'F20');
    }
}
