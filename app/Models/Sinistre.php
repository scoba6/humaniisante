<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sinistre extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'sinistres';

    protected $fillable = [
        'prestataire_id',
        'famille_id',
        'membre_id',
        'acte_id',
        'nataff_id',
        'numsin',
        'datsai',
        'datmal',
        'natact', //Nature Acte
        'nataff', // ANture Affection
        'mnttot', // Montant total
        'mnbase',
        'mnttmo', // Ticket moderateur
        'mntass', // Part Humanniis
        'attachements'
    ];

    public static function boot(): void
    {
        parent::boot();  
        static::created(function (Model $model) {
            // Ce code s'exécute quand un sinistre est créé
            // Numéro de sinistre
            $sh= 'SH'; // Identifaction du site
            //Generation du N° de FACTURE
            switch (Str::length($model->id)) {
                case 1:
                    $model->numsin = ($sh .Carbon::now()->format('y').Carbon::now()->format('m').'00'.$model->id);
                    break;
                case 2:
                    $model->numsin = ($sh .Carbon::now()->format('y').Carbon::now()->format('m').'0'.$model->id);
                    break;

                default:
                    $model->numsin = ($sh.Carbon::now()->format('y').Carbon::now()->format('m').$model->id);
                    break;
            }
            $model->save(); 
        });
    }

 
    /**
     * Get the prestaire that owns the Sinistre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class);
    }

    /**
     * Get the famille that owns the Sinistre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }


    /**
     * Get the membre that owns the Sinistre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Get the humpagen that owns the Sinistre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function humpagen(): BelongsTo
    {
        return $this->belongsTo(Humpargen::class, 'nataff_id', 'id');
    }


}
