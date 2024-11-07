<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinistreActe extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'sinistre_actes';

    protected $fillable = [
        'sinistre_id', // Sinistre
        'acte_id', //Acte concernée
        'qteact',
        'mntact',
        'mntxlu',
        'mnttot',
        'tmoact',
        'mntass'
    ];


    /**
     * Get the sinistre associated with the SinistreActe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sinistre(): HasOne
    {
        return $this->hasOne(Sinistre::class, 'sinistre_id', 'id');
    }





}
