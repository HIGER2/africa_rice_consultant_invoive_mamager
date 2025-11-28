<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasUuid;
    // protected $table = 'supervisors';

    protected $fillable = [
        'name',
        'uuid',
        'last_name',
        'email',
        'phone',
        'resno',
        'position',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'supervisor_id', 'id');
    }
}
