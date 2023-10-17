<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formule extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    protected $fillable = [
        'libfrm',
        'comfrm',
        'ambfrm',
        'tauamb',
        'tauhos',
        'limacc',
        'limhos',
        'limbio',
        'limrad',
        'limchr',
        'limpla',
        'limact',
    ];

    /**
     * Get all of the option for the Formule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Get the territo that owns the Formule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function territo(): BelongsTo
    {
        return $this->belongsTo(Territo::class, 'foreign_key', 'other_key');
    }
}
