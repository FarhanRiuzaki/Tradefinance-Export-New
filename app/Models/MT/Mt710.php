<?php

namespace App\Models\MT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mt710 extends Model
{
    use HasFactory;

    protected $connection   = 'mt_connection';
    protected $table        = 'mt710';
}
