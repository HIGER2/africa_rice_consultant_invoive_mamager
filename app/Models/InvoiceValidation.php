<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceValidation extends Model
{
    protected $fillable = [
        'supervisor_period_validated',
        'supervisor_report_validated',
        'supervisor_budget_validated',

        'hr_period_validated',
        'hr_report_validated',
        'hr_clearance_validated',

        'finance_period_validated',
        'finance_report_validated',
        'finance_budget_validated',
        'finance_clearance_validated',
        'finance_contract_validated'
    ];
    protected $casts = [
        // Supervisor
        'supervisor_period_validated' => 'boolean',
        'supervisor_report_validated' => 'boolean',
        'supervisor_budget_validated' => 'boolean',

        // HR
        'hr_period_validated' => 'boolean',
        'hr_report_validated' => 'boolean',
        'hr_clearance_validated' => 'boolean',

        // Finance
        'finance_period_validated' => 'boolean',
        'finance_report_validated' => 'boolean',
        'finance_budget_validated' => 'boolean',
        'finance_clearance_validated' => 'boolean',
        'finance_contract_validated' => 'boolean',
    ];
}
