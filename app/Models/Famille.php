<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Famille extends Model
{
    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'familles';

    protected $fillable = [
       'matfam',
       'nomfam',
       'vilfam',
       'payfam',
       'adrfam',
       'commercial_id'
    ];

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
}
