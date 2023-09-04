<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Qualite extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'sinistres';

    protected $fillable = [
        'libqlt',
        'abrqlt'
    ];
}
