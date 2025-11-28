<?php

namespace App\Models;

use App\Traits\HasDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{

    use HasDate;
    protected $fillable = [
        'consultant_id',
        // 'institution',
        'uuid',
        'invoice_number',
        'date',
        'location',
        'contract_period_from',
        'contract_period_to',
        'is_forfaitaire_contract',
        'forfaitaire_amount',
        'honoraires_mensuel',
        'jours_travailles',
        'date_from',
        'date_to',
        'honoraires_a_payer',
        'rapport_activite_required',
        'rapport_activite_file',
        'clearance_required',
        'clearance_file',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            // Generate a unique invoice number
            $invoice->invoice_number = 'INV-' . strtoupper(uniqid());
            $invoice->uuid = (string) \Illuminate\Support\Str::uuid();
            // if (empty($invoice->date)) {
            //     $invoice->date = now()->toDateString();
            // }
        });
    }

    public function scopeByRole($query)
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'hr') {
            // HR voit toutes les factures
            return $query;
        }

        if ($role === 'finance') {
            // Finance voit uniquement les factures en attente de traitement finance
            return $query->whereIn('status', ['pending_finance', 'approuved']);
        }

        if ($role === 'supervisor') {
            // Supervisor voit uniquement les factures de ses consultants en attente de validation
            return $query->whereHas('consultant', function ($q) use ($user) {
                $q->where('supervisor_id', $user->supervisor->id);
            });
        }

        if ($role === 'consultant') {
            // Le consultant voit uniquement ses propres factures
            return $query->where('consultant_id', $user->id);
        }

        // Par défaut, ne rien retourner si le rôle n’est pas reconnu
        // return $query->whereRaw('0 = 1');
        return $query;
    }


    public function invoiceValidation()
    {
        return $this->hasOne(InvoiceValidation::class);
    }
    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }
}
