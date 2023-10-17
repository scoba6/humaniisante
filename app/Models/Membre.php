<?php

namespace App\Models;

use App\Models\Formule;
use Illuminate\Support\Str;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membre extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'familles_membres';

    protected $fillable = [
        'famille_id',
        'qualite_id',
        'formule_id',
        'groupe_id',
        'option_id',
        'nommem',
        'datnai',
        'commem',
        'agemem',
        'valfrm',
        'framem',
        'optmem',
        'denmem',
        'commem'
    ];

    public static function boot(): void
    {
        parent::boot();
        static::created(function (Model $model) {
            // Ce code s'exécute quand un membre est créée

            $mat = 'H';
            $frm = Formule::find($model->formule_id)?->libfrm; // nom de la formule
            $flt = Str::substr($frm, 0, 1);  //1ere lettre de la formule
            //Generation du N° de FACTURE
            switch (Str::length($model->id)) {
                case 1:
                    $model->matmem = ($mat . $flt . '000' . $model->id);
                    break;
                case 2:
                    $model->matmem = ($mat . $flt . '00' . $model->id);
                    break;
                case 3:
                    $model->matmem = ($mat . $flt . '0' . $model->id);
                    break;

                default:
                    $model->matmem = ($mat . $flt . $model->id);
                    break;
            }
            $model->save();
        });
    }

    /**
     * Get the famille that owns the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function famille(): BelongsTo
    {
        return $this->belongsTo(Famille::class);
    }

     /**
     * Get the qualite that owns the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qualite(): BelongsTo
    {
        return $this->belongsTo(Qualite::class);
    }

    /**
     * Get the formule that owns the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formule(): BelongsTo
    {
        return $this->belongsTo(Formule::class);
    }

    /**
     * Get the Option that owns the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Option(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }

    /**
     * Get all of the cotisations for the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cotisations(): HasMany
    {
        return $this->hasMany(Cotisations::class);
    }

    /**
     * Get the groupe that owns the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id','id');
    }

    /**
     * Get all of the sinistre for the Membre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class);
    }
}
