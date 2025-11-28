<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statut de la facture</title>
</head>
<body>
    {{-- <p>Bonjour {{ $invoice->consultant->name }},</p> --}}

    <p>{{ $messageContent }}</p>

    <h3>Détails de la facture :</h3>
    <ul>
        <li><strong>Numéro de facture :</strong> {{ $invoice->invoice_number }}</li>
        <li><strong>Consultant :</strong> {{ $invoice->consultant->name }} {{ $invoice->consultant->last_name }}</li>
        <li><strong>Montant à payer :</strong> {{ $invoice->honoraires_a_payer }} </li>
        <li><strong>Période :</strong> {{ $invoice->date_from }} à {{ $invoice->date_to }}</li>
        <li><strong>Statut :</strong> {{ $invoice->status }}</li>
    </ul>

    @if(isset($invoice->invoiceValidation))
    <h4>Statut de validation :</h4>
    <ul>
        <li>Superviseur : 
            {{ $invoice->invoiceValidation->supervisor_period_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->supervisor_report_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->supervisor_budget_validated ? '✅' : '❌' }}
        </li>
        <li>RH : 
            {{ $invoice->invoiceValidation->hr_period_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->hr_report_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->hr_clearance_validated ? '✅' : '❌' }}
        </li>
        <li>Finance : 
            {{ $invoice->invoiceValidation->finance_period_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->finance_report_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->finance_budget_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->finance_clearance_validated ? '✅' : '❌' }},
            {{ $invoice->invoiceValidation->finance_contract_validated ? '✅' : '❌' }}
        </li>
    </ul>
    @endif

    <p>Merci de vous connecter à la plateforme pour traiter ou suivre cette facture.</p>
    <p>Cordialement,<br>L’équipe de gestion des factures</p>
</body>
</html>
