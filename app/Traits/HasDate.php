<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait HasDate
{
    protected static function bootHasDate()
    {
        static::creating(function ($model) {
            if (empty($model->date)) {
                $model->date = Carbon::now()->toDateString();
            }
        });
    }
}
