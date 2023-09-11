<?php

namespace App\Models;

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
        'datsai',
        'datmal',
        'natact', //Nature Acte
        'nataff', // ANture Affection
        'mnttot', // Montant total
        'mnbase',
        'mnttmo', // Ticket moderateur
        'mntass', // Part Humanniis
    ];

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
}
