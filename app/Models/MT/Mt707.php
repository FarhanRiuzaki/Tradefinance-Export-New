<?php

namespace App\Models\MT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mt707 extends Model
{
    use HasFactory;

    protected $connection   = 'mt_connection';
    protected $table        = 'mt707';

    public function mt700()
    {
        return $this->hasOne(Mt700::class, 'F20', 'F20');
    }
}
