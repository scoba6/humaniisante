<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReglementSinistre extends Model
{
    use HasFactory, Userstamps, SoftDeletes;
    protected $table = 'reglement_sinistres';
    protected $fillable = [
        'reglement_id',
        'sinistre_id',
        'mntass'
    ];

    /**
     * Get the reglement associated with the ReglementSinistre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reglement(): HasOne
    {
        return $this->hasOne(Reglement::class, 'reglement_id', 'id');
    }




}
