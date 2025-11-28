<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultant_id',
        'bank_name',
        'iban',
        'swift_code',
    ];

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }
}
