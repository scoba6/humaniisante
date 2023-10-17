<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotisations extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    protected $table = 'familles_membres_cotisations';

    protected $fillable = [
        'membre_id',
        'mntcot',
        'mnttps',
        'mntcss',
        'mntadh',
        'mntacc',
        'mntttc',
        'datcot',
        'datval',
    ];

    /**
     * Get the membre that owns the Cotisations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }
}
