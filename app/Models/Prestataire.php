<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Prestataire extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $fillable = [
        'rsopre',
        'adrpre',
        'telpre',
        'maipre',
     ];
}
