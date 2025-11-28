<?php

namespace App\Observers;

use Illuminate\Support\Str;

class UuidObserver
{
    public function creating($model)
    {
        if ($model->isFillable('uuid') && empty($model->uuid)) {
            $model->uuid = Str::uuid()->toString();
        }
    }
}
