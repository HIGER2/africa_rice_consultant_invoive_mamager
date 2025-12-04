<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'consultant_id' => $this->consultant_id,
            'invoice_number' => $this->invoice_number,
            'location' => $this->location,

            'is_forfaitaire_contract' => $this->is_forfaitaire_contract ? 'Yes' : 'No',
            'forfaitaire_amount' => $this->forfaitaire_amount,
            'honoraires_mensuel' => $this->honoraires_mensuel,
            'jours_travailles' => $this->jours_travailles,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'honoraires_a_payer' => $this->honoraires_a_payer,
            'status' => $this->status,
            'rapport_activite_required' => $this->rapport_activite_required ? 'Yes' : 'No',
            'rapport_activite_file' => $this->rapport_activite_file
                ? Storage::url($this->rapport_activite_file)
                : null,
            'clearance_required' => $this->clearance_required ? 'Yes' : 'No',
            'clearance_file' => $this->clearance_file
                ? Storage::url($this->clearance_file)
                : null,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            // 'id' => $this->consultant->id ?? null,
            // 'uuid' => $this->consultant->uuid ?? null,
            'resno' => $this->consultant->resno ?? null,
            'name' => $this->consultant->name ?? null,
            'last_name' => $this->consultant->last_name ?? null,
            'contract_period_from' => $this->consultant->date_from,
            'contract_period_to' => $this->consultant->date_to,
            'email' => $this->consultant->email ?? null,
            'phone' => $this->consultant->phone ?? null,
            'position' => $this->consultant->position ?? null,
            'department' => $this->consultant->department ?? null,
            'bank_name' => $this->consultant->bankDetails->bank_name ?? null,
            'iban' => $this->consultant->bankDetails->iban ?? null,
            'swift_code' => $this->consultant->bankDetails->swift_code ?? null,
            // Flatten consultant info
            // 'consultant' => [

            //     // ajoute ici tous les champs que tu veux exposer
            //     'bank_details' => [

            //     ],
            // ],
            // Invoice validation status
            'supervisor_period_validated'   => (bool) $this->invoiceValidation?->supervisor_period_validated ? "Yes" : 'No',
            'supervisor_report_validated'   => (bool) $this->invoiceValidation?->supervisor_report_validated ? "Yes" : 'No',
            'supervisor_budget_validated'   => (bool) $this->invoiceValidation?->supervisor_budget_validated ? "Yes" : 'No',

            'hr_period_validated'           => (bool) $this->invoiceValidation?->hr_period_validated ? "Yes" : 'No',
            'hr_report_validated'           => (bool) $this->invoiceValidation?->hr_report_validated ? "Yes" : 'No',
            'hr_clearance_validated'        => (bool) $this->invoiceValidation?->hr_clearance_validated ? "Yes" : 'No',

            'finance_period_validated'      => (bool) $this->invoiceValidation?->finance_period_validated ? "Yes" : 'No',
            'finance_report_validated'      => (bool) $this->invoiceValidation?->finance_report_validated ? "Yes" : 'No',
            'finance_budget_validated'      => (bool) $this->invoiceValidation?->finance_budget_validated ? "Yes" : 'No',
            'finance_clearance_validated'   => (bool) $this->invoiceValidation?->finance_clearance_validated ? "Yes" : 'No',
            'finance_contract_validated'    => (bool) $this->invoiceValidation?->finance_contract_validated ? "Yes" : 'No',
        ];
    }
}
