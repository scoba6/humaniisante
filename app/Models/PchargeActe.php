<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PchargeActe extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'pcharge_actes'; //Actes dans la PC

    protected $fillable = [
        'pcharge_id', // PC
        'acte_id', //Acte concernée
        'qteact', // Qté
        'mntact', //PU
    ];



    /**
     * Get the pchg associated with the PchargeActe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pchg(): HasOne
    {
        return $this->hasOne(Pcharge::class,'pcharge_id','id' );
    }
}
