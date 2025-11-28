<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'resno',
        'supervisor_id',
        'email',
        'email_cgiar',
        'phone',
        'name',
        'last_name',
        'gender',
        'nationality',
        'country_of_birth',
        'town_city',
        'date_from',
        'date_to',
        'position',
        'resource_type',
        'job_level',
        // 'supervisor_position',
        // 'supervisor',
        'costc',
        'department',
        'dutypost',
        'original_hire_date',
        'seniority',
        'birthdate',
        'nationality_at_birth',
        'marital_status',
        'seconded_personnel',
        'shared_working_arrangement',
        'root',
        'division',
        'group',
        'cg_unit',
        'sub_unit',
        'bg_level',
        'cgiar_workforce_group',
        'dutypost_classification',
        'education_level',
        'host_center',
        'hosted_personnel',
        'hosted_seconded_personnel',
        'secondary_nationality',
    ];

    protected $casts  = [
        // 'date_from' => 'date',
        // 'date_to' => 'date',
        // 'original_hire_date' => 'date',
        // 'birthdate' => 'date',
        // 'seconded_personnel' => 'boolean',
        // 'shared_working_arrangement' => 'boolean',
        // 'hosted_personnel' => 'boolean',
        // 'hosted_seconded_personnel' => 'boolean',

    ];
    public function bankDetails()
    {
        return $this->hasOne(BankDetail::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
