<?php

namespace App\Models;

use App\Models\PurchaseApproval;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'pin',
        'role',
        'password',
        'otp_expires_at',
        'status',
        'consultant_id',
        'supervisor_id',
        'employee_id',
        'uuid',
    ];

    protected $hidden = ['password', 'pin', 'remember_token'];

    // Relations with Consultant and Employee
    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }


    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Role check methods
    public function isConsultant(): bool
    {
        return $this->role === 'consultant';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isHR(): bool
    {
        return $this->role === 'hr';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasRole(...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
