<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Famille extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'familles';

    protected $fillable = [
       'matfam',
       'nomfam',
       'statut',
       'numcdg',
       'vilfam',
       'payfam',
       'adrfam',
       'commercial_id'
    ];

    public static function boot(): void
    {
        parent::boot();
        static::created(function (Model $model) {
            // Ce code s'exécute quand un membre est créée

            $pre = 'CDG-';
            $randomNumber = random_int(100000, 999999); // génération aléatoir entre ces 2 valeurs

            //Generation du N° de CDG //Convention de gestion
            $model->numcdg = ($pre.$randomNumber.'H');

            $model->save();
        });
    }

    /**
     * Get the membre associated with the Famille
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function membre(): HasOne
    {
        return $this->hasOne(Membre::class);
    }

    /**
     * Get the commercial that owns the Famille
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commercial(): BelongsTo
    {
        return $this->belongsTo(Commercial::class, 'commercial_id', 'id');
    }

    /**
     * Get all of the sinistre for the Famille
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sinistre(): HasMany
    {
        return $this->hasMany(Sinistre::class);
    }
}
