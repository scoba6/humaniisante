<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Charge extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'charges'; //Prise en charge

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

     public static function boot(): void
    {
        parent::boot();
        static::created(function (Model $model) {
            // Ce code s'exécute quand un membre est créée

            $pre = 'PC-';
            $randomNumber = random_int(100000, 999999); // génération aléatoir entre ces 2 valeurs
           
            //Generation du N° de CDG //Convention de gestion
            $model->numpch = ($pre.$randomNumber.'H');
            
            $model->save();
        });
    }



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

     /**
      * Get all of the actes for the Pcharge
      *
      * @return \Illuminate\Database\Eloquent\Relations\HasMany
      */
     public function actes(): HasMany
     {
         return $this->hasMany(Chacte::class, 'charge_id');
     }

     /**
      * Get the beneficiaire that owns the Pcharge
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function beneficiaire(): BelongsTo
     {
         return $this->belongsTo(Membre::class, 'membre_id', 'id');
     }

     /**
      * Get the prestataire that owns the Pcharge
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function prestataire(): BelongsTo
     {
         return $this->belongsTo(Prestataire::class, 'prestataire_id', 'id');
     }
}
