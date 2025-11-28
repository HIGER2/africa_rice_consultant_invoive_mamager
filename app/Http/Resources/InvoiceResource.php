<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'consultant_id' => $this->consultant_id,
            'invoice_number' => $this->invoice_number,
            'location' => $this->location,
            'contract_period_from' => $this->contract_period_from,
            'contract_period_to' => $this->contract_period_to,
            'is_forfaitaire_contract' => $this->is_forfaitaire_contract ? 'Oui' : 'Non',
            'forfaitaire_amount' => $this->forfaitaire_amount,
            'honoraires_mensuel' => $this->honoraires_mensuel,
            'jours_travailles' => $this->jours_travailles,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'honoraires_a_payer' => $this->honoraires_a_payer,
            'status' => $this->status,
            'rapport_activite_required' => $this->rapport_activite_required ? 'Oui' : 'Non',
            'rapport_activite_file' => $this->rapport_activite_file
                ? Storage::url($this->rapport_activite_file)
                : null,
            'clearance_required' => $this->clearance_required ? 'Oui' : 'Non',
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
            "validations" =>  [
                'supervisor_period_validated'   => (bool) $this->invoiceValidation?->supervisor_period_validated,
                'supervisor_report_validated'   => (bool) $this->invoiceValidation?->supervisor_report_validated,
                'supervisor_budget_validated'   => (bool) $this->invoiceValidation?->supervisor_budget_validated,

                'hr_period_validated'           => (bool) $this->invoiceValidation?->hr_period_validated,
                'hr_report_validated'           => (bool) $this->invoiceValidation?->hr_report_validated,
                'hr_clearance_validated'        => (bool) $this->invoiceValidation?->hr_clearance_validated,

                'finance_period_validated'      => (bool) $this->invoiceValidation?->finance_period_validated,
                'finance_report_validated'      => (bool) $this->invoiceValidation?->finance_report_validated,
                'finance_budget_validated'      => (bool) $this->invoiceValidation?->finance_budget_validated,
                'finance_clearance_validated'   => (bool) $this->invoiceValidation?->finance_clearance_validated,
                'finance_contract_validated'    => (bool) $this->invoiceValidation?->finance_contract_validated,
            ]

            //  "validations" => $this->invoiceValidation ? [
            //     'supervisor_period_validated'   => (bool) $this->invoiceValidation->supervisor_period_validated,
            //     'supervisor_report_validated'   => (bool) $this->invoiceValidation->supervisor_report_validated,
            //     'supervisor_budget_validated'   => (bool) $this->invoiceValidation->supervisor_budget_validated,

            //     'hr_period_validated'           => (bool) $this->invoiceValidation->hr_period_validated,
            //     'hr_report_validated'           => (bool) $this->invoiceValidation->hr_report_validated,
            //     'hr_clearance_validated'        => (bool) $this->invoiceValidation->hr_clearance_validated,

            //     'finance_period_validated'      => (bool) $this->invoiceValidation->finance_period_validated,
            //     'finance_report_validated'      => (bool) $this->invoiceValidation->finance_report_validated,
            //     'finance_budget_validated'      => (bool) $this->invoiceValidation->finance_budget_validated,
            //     'finance_clearance_validated'   => (bool) $this->invoiceValidation->finance_clearance_validated,
            //     'finance_contract_validated'    => (bool) $this->invoiceValidation->finance_contract_validated,
            // ] : null
            // "invoiceValidation" => $this->invoiceValidation,

            // 'supervisor_period_validated'   => (bool) $this->invoiceValidation?->supervisor_period_validated,
            // 'supervisor_report_validated'   => (bool) $this->invoiceValidation?->supervisor_report_validated,
            // 'supervisor_budget_validated'   => (bool) $this->invoiceValidation?->supervisor_budget_validated,

            // 'hr_period_validated'           => (bool) $this->invoiceValidation?->hr_period_validated,
            // 'hhr_report_validated'          => (bool) $this->invoiceValidation?->hr_report_validated,
            // 'hr_clearance_validated'        => (bool) $this->invoiceValidation?->hr_clearance_validated,

            // 'finance_period_validated'      => (bool) $this->invoiceValidation?->finance_period_validated,
            // 'finance_report_validated'      => (bool) $this->invoiceValidation?->finance_report_validated,
            // 'finance_budget_validated'      => (bool) $this->invoiceValidation?->finance_budget_validated,
            // 'finance_clearance_validated'   => (bool) $this->invoiceValidation?->finance_clearance_validated,
            // 'finance_contract_validated'    => (bool) $this->invoiceValidation?->finance_contract_validated,

        ];
    }
}
