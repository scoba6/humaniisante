<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reglement extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $fillable = [
        'prestataire_id',
        'numreg',
        'mntreg',
        'estclo',
        'modreg',
        'comment'
    ];

    public static function boot(): void
    {
        parent::boot();
        static::created(function (Model $model) {

            // Ce code s'exécute quand un REGLEME?T est créé pour un ou des sinistres
            $model->numreg = 'RS'.str_pad($model->id, 7, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

     /**
     * Get the prestatait that owns the Reglement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class, 'prestataire_id', 'id');
    }

    /**
     * Get all of the sinistres for the Reglement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sinistres(): HasMany
    {
        return $this->hasMany(ReglementSinistre::class, );
    }


}
