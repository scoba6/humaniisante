<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chacte extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'chactes'; //Actes dans la PC

    protected $fillable = [
        'charge_id', // PC
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
        return $this->hasOne(Charge::class,'charge_id','id');
    }
}
