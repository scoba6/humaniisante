<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestataire extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $fillable = [
        'rsopre',
        'adrpre',
        'telpre',
        'maipre',
        'locpre'
     ];

     /**
      * Get all of the sinistres for the Prestataire
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function sinistres(): HasMany
     {
         return $this->hasMany(Sinistre::class, 'foreign_key', 'local_key');
     }

     /**
      * Get the localite that owns the Prestataire
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function localite(): BelongsTo
     {
         return $this->belongsTo(Localite::class, 'locpre', 'id');
     }
}
