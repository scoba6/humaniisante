<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acte extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    protected $table = 'actes';

    protected $fillable = [
        'libact'
     ];
}
