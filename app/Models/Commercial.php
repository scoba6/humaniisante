<?php

namespace App\Models;

use Illuminate\Support\Str;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Commercial extends Model
{

    use HasFactory, Userstamps, SoftDeletes;

    protected $table = 'commercials';

    protected $fillable = [
        'nomcom',
        'taucom',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::created(function (Model $model) {

            // Ce code s'exécute quand un commercial est créée
            $mat = 'C0M';

            //Generation du code commerciale
            switch (Str::length($model->id)) {
                case 1:
                    $model->matcom = ($mat . '000' . $model->id);
                    break;
                case 2:
                    $model->matcom = ($mat . '00' . $model->id);
                    break;
                case 3:
                    $model->matcom = ($mat . '0' . $model->id);
                    break;

                default:
                    $model->matcom = ($mat . $model->id);
                    break;
            }
            $model->save();
        });
    }

    /**
     * Get the famille associated with the Commercial
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function famille(): HasOneOrMany
    {
        return $this->hasOne(Fammille::class);
    }


}
