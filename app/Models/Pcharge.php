<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pcharge extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $fillable = [

        'prestataire_id',
        'famille_id',
        'membre_id',
        'redac_id',
        'ctrler_id',
        'datemi',
        'datexp',
        'numpch',
   
     ];


     /**
      * Get the redac that owns the Pcharge
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function redac(): BelongsTo
     {
         return $this->belongsTo(User::class, 'redac_id', 'id');
     }

     /**
      * Get the ctrler that owns the Pcharge
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function ctrler(): BelongsTo
     {
         return $this->belongsTo(User::class, 'ctrler_id', 'id');
     }

     public function comments(): MorphMany
     {
         return $this->morphMany(Acte::class, 'commentable');
     }
    
}
