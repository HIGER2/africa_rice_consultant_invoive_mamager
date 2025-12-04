<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\BankDetail;
use App\Models\Consultant;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\InvoiceNotificationSupervisorMail;
use App\Mail\InvoiceSubmittedMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {

        $defaultFrom = Carbon::now()->startOfMonth()->toDateString();
        $defaultTo   = Carbon::now()->endOfMonth()->toDateString();

        $from = $request->filled('from') ? $request->from : $defaultFrom;
        $to   = $request->filled('to') ? $request->to : $defaultTo;

        $query = Invoice::ByRole()->with('consultant', 'consultant.bankDetails', 'invoiceValidation')
            ->orderByDesc('created_at');

        // FILTRE : DATE
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [
                $from,
                $to
            ]);
        }

        // FILTRE : RECHERCHE
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%$search%")
                    ->orWhereHas('consultant', function ($q2) use ($search) {
                        $q2->where('name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%");
                    });
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        return Inertia::render('views/Invoice', [
            'invoices' => InvoiceResource::collection($invoices),
            'filters' => [
                'from' => $from,
                'to' => $to,
                'search' => $request->search,
            ],
            'urls'     => [
                'index' => route('invoices.index'),
                'create' => route('invoices.create'),
            ]
        ]);
    }


    public function show($uuid)
    {
        $invoice = Invoice::with('consultant', 'consultant.bankDetails', 'invoiceValidation')
            ->firstWhere('uuid', $uuid);
        Log::info('Showing invoice', ['invoice' => $invoice]);

        if (!$invoice) {
            // Option 1 → retour Inertia avec page d’erreur
            return Inertia::render('ErrorPage', [
                'message' => "Invoice not found"
            ])->toResponse(request())->setStatusCode(404);

            // Option 2 → redirection simple
            // return redirect()->route('invoices.index')->with('error', 'Invoice not found');
        }


        return Inertia::render('views/InvoiceDetail', [
            'invoice' => new InvoiceResource($invoice),
            'urls' => [
                'create' => route('invoices.create'),
                'index'  => route('invoices.index'),
            ],
        ]);
    }


    public function create()
    {
        $user = Auth::user();
        return Inertia::render('views/CreateInvoice', [
            'consultant' => $user?->consultant?->load('bankDetails'),
            'isEdit' => false,
            'actionUrl' => route('consultants.store'),
            'cancelUrl' => route('consultants.index'),
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'consultant.resno' => 'required|string',
            'consultant.name' => 'required|string',
            'consultant.last_name' => 'required|string',
            'consultant.email' => 'required|email',
            'consultant.phone' => 'required|numeric',
            'consultant.institution' => 'required|string',
            'consultant.position' => 'required|string',

            'bank.bank_name' => 'required|string',
            'bank.iban' => 'required|string',
            'bank.swift_code' => 'nullable|string',

            'invoice.location' => 'required|string',
            'invoice.date_from' => 'required|date',
            'invoice.date_to' => 'required|date',
            'invoice.honoraires_mensuel' => 'required|numeric',
            'invoice.jours_travailles' => 'required|numeric',
            'invoice.date_from' => 'required|date',
            'invoice.date_to' => 'required|date|after_or_equal:invoice.date_from',
            'invoice.honoraires_a_payer' => 'required|numeric',

            'invoice.is_forfaitaire_contract' => 'required|boolean',
            'invoice.forfaitaire_amount' => 'required_if:invoice.is_forfaitaire_contract,1|nullable|numeric',

            'invoice.rapport_activite_required' => 'required|boolean',
            'invoice.rapport_activite_file' => 'required_if:invoice.rapport_activite_required,1|nullable|file|mimes:pdf,doc,docx|max:5120',

            'invoice.clearance_required' => 'required|boolean',
            'invoice.clearance_file' => 'required_if:invoice.clearance_required,1|nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        try {

            // $invoiceData = $validated['invoice'];
            DB::beginTransaction();
            // 🔹 1. Update consultant
            $consultant = Auth::user()->consultant;
            Log::info('Authenticated user consultant ID: ' . $consultant->id);
            Log::info($validated);
            // $consultant = Consultant::findOrFail($validated['consultant']['id']);
            $consultant->update($validated['consultant']);
            // 🔹 2. Update or create bank details
            BankDetail::updateOrCreate(
                ['consultant_id' => $consultant->id],
                $validated['bank']
            );

            // 🔹 4. Upload des fichiers si fournis
            // if ($request->hasFile('invoice.rapport_activite_file')) {
            //     $path = $request->file('invoice.rapport_activite_file')
            //         ->store('consultants/rapport_activite', 'public');
            //     $invoiceData['rapport_activite_file'] = $path;
            // }

            // if ($request->hasFile('invoice.clearance_file')) {
            //     $path = $request->file('invoice.clearance_file')
            //         ->store('consultants/clearance', 'public');
            //     $invoiceData['clearance_file'] = $path;
            // }

            if ($request->hasFile('invoice.clearance_file')) {
                $clearance = $request->file('invoice.clearance_file');
                $originalName = pathinfo($clearance->getClientOriginalName(), PATHINFO_FILENAME); // nom sans extension
                $extension = $clearance->getClientOriginalExtension(); // extension du fichier
                $uniqueSuffix = uniqid('_', true); // génère un identifiant unique
                $fileName = $originalName . $uniqueSuffix . '.' . $extension;
                $path = $clearance->storeAs('invoices/clearance', $fileName, 'public');
                $validated['invoice']['clearance_file'] = $path;
            }

            if ($request->hasFile('invoice.rapport_activite_file')) {
                $rapport = $request->file('invoice.rapport_activite_file');
                $originalName = pathinfo($rapport->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $rapport->getClientOriginalExtension();
                $uniqueSuffix = uniqid('_', true);
                $fileName = $originalName . $uniqueSuffix . '.' . $extension;
                $path = $rapport->storeAs('invoices/rapport_activite', $fileName, 'public');
                $validated['invoice']['rapport_activite_file'] = $path;
            }

            // 🔹 3. Create invoice
            $invoice = Invoice::create([
                ...$validated['invoice'],
                'consultant_id' => $consultant->id,
            ]);

            DB::commit();

            // Créer la ligne de validation vide
            $invoice->invoiceValidation()->create([]);

            // Envoi mail au consultant
            $consultant = $invoice->consultant;
            $supervisor = $consultant->supervisor;

            Mail::to($consultant->email)
                ->send(new InvoiceSubmittedMail($invoice));

            // Envoi mail au superviseur
            Mail::to($supervisor->email)
                ->send(new InvoiceNotificationSupervisorMail($invoice));

            return redirect()->route('invoices.show', $invoice->uuid)
                ->with('success', 'Facture enregistrée et emails envoyés.');

            return redirect()->route('invoices.index')->with('success', 'Consultant created successfully.');
            // return response()->json([
            //     'message' => 'Invoice created successfully',
            //     'invoice' => $invoice
            // ]);
        } catch (\Throwable $th) {
            Log::error('Error creating consultant: ' . $th->getMessage(), ['stack' => $th->getTraceAsString()]);
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while creating the consultant. Please try again.');
        }
    }

    public function validateInvoice(Request $request, $uuid)
    {
        try {

            $user = Auth::user();
            $invoice = Invoice::with('consultant', 'invoiceValidation')->firstWhere('uuid', $uuid);
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
            $validated = [
                "hr" => [
                    'hr.hr_period_validated' => 'sometimes|boolean',
                    'hr.hr_report_validated' => 'sometimes|boolean',
                    'hr.hr_clearance_validated' => 'sometimes|boolean',
                ],
                "supervisor" => [
                    'supervisor.supervisor_period_validated' => 'sometimes|boolean',
                    'supervisor.supervisor_report_validated' => 'sometimes|boolean',
                    'supervisor.supervisor_budget_validated' => 'sometimes|boolean',
                ],
                "finance" => [
                    'finance.finance_period_validated' => 'sometimes|boolean',
                    'finance.finance_report_validated' => 'sometimes|boolean',
                    'finance.finance_clearance_validated' => 'sometimes|boolean',
                    'finance.finance_contract_validated' => 'sometimes|boolean',
                ],
            ];

            $data = $request->validate([
                'service' => 'required|string|in:supervisor,hr,finance',
            ]);

            $validation = $request->validate($validated[$data['service']]);

            $iv = $invoice->invoiceValidation()->updateOrCreate(
                ['invoice_id' => $invoice->id], // clé étrangère pour retrouver la validation
                $validation[$data['service']]                     // données envoyées depuis la requête
            );
            // $iv->update($validation);
            // Mettre à jour uniquement les champs du service courant
            switch ($data['service']) {
                case 'supervisor':
                    $allValid = $iv->supervisor_period_validated && $iv->supervisor_report_validated && $iv->supervisor_budget_validated;
                    $invalidFields = [];
                    foreach (['supervisor_period_validated', 'supervisor_report_validated', 'supervisor_budget_validated'] as $f) {
                        if (!$iv->$f) $invalidFields[] = $f;
                    }
                    break;
                case 'hr':
                    $allValid = $iv->hr_period_validated && $iv->hr_report_validated && $iv->hr_clearance_validated;
                    $invalidFields = [];
                    foreach (['hr_period_validated', 'hr_report_validated', 'hr_clearance_validated'] as $f) {
                        if (!$iv->$f) $invalidFields[] = $f;
                    }
                    break;
                case 'finance':
                    $allValid = $iv->finance_period_validated && $iv->finance_report_validated  && $iv->finance_clearance_validated && $iv->finance_contract_validated;
                    $invalidFields = [];
                    foreach (['finance_period_validated', 'finance_report_validated', 'finance_budget_validated', 'finance_clearance_validated', 'finance_contract_validated'] as $f) {
                        if (!$iv->$f) $invalidFields[] = $f;
                    }
                    break;
            }

            // $iv->save();

            $nextService = null;
            $revertService = null;

            $consultantEmail = $invoice->consultant->email;
            $supervisorEmail = $invoice->consultant->supervisor->email;
            $previousServiceEmail = [];

            if ($allValid) {
                // Tout est validé, passe à l'étape suivante
                switch ($data['service']) {
                    case 'supervisor':
                        $invoice->status = 'pending_hr';
                        $nextService = 'hr';
                        $messageToConsultant = "Bonjour {$invoice->consultant->name}, votre facture {$invoice->invoice_number} a été validée par le superviseur et est maintenant en attente du service RH.";
                        $messageToService = "Bonjour, une nouvelle facture {$invoice->invoice_number} de {$invoice->consultant->name} est prête pour traitement. Merci de la valider.";
                        break;
                    case 'hr':
                        $invoice->status = 'pending_finance';
                        $nextService = 'finance';
                        $messageToConsultant = "Bonjour {$invoice->consultant->name}, votre facture {$invoice->invoice_number} a été validée par le service RH et est maintenant en attente du service Finance.";
                        $messageToService = "Bonjour, une nouvelle facture {$invoice->invoice_number} de {$invoice->consultant->name} est prête pour traitement. Merci de la valider.";
                        break;
                    case 'finance':
                        $invoice->status = 'approved';
                        $messageToConsultant = "Bonjour {$invoice->consultant->name}, votre facture {$invoice->invoice_number} a été entièrement validée et approuvée par tous les services.";
                        $messageToService = "La facture {$invoice->invoice_number} de {$invoice->consultant->name} est maintenant complètement approuvée.";
                        break;
                }
            } else {
                // Tout n'est pas validé, renvoyer à l'étape précédente
                switch ($data['service']) {
                    case 'supervisor':
                        $revertService = 'supervisor';
                        $messageToConsultant = "Bonjour {$invoice->consultant->name}, votre facture {$invoice->invoice_number} n'a pas été entièrement validée par le superviseur. Champs non validés : " . implode(', ', $invalidFields) . ".";
                        $previousServiceEmail = $supervisorEmail;
                        break;
                    case 'hr':
                        $invoice->status = 'pending_supervisor';
                        $revertService = 'supervisor';
                        $messageToConsultant = "Bonjour {$invoice->consultant->name}, votre facture {$invoice->invoice_number} n'a pas été entièrement validée par le service RH. Champs non validés : " . implode(', ', $invalidFields) . ". Elle est renvoyée au superviseur.";
                        $previousServiceEmail = $supervisorEmail;

                        break;
                    case 'finance':
                        $invoice->status = 'pending_hr';
                        $revertService = 'hr';
                        $messageToConsultant = "Bonjour {$invoice->consultant->name}, votre facture {$invoice->invoice_number} n'a pas été entièrement validée par le service Finance. Champs non validés : " . implode(', ', $invalidFields) . ". Elle est renvoyée au service RH.";
                        $previousServiceEmail = User::where('role', 'hr')->pluck('email')->toArray();
                        break;
                }
            }

            $invoice->save();

            // Envoi mail uniquement si nécessaire
            if (!$allValid) {
                // Facture non validée → mail au consultant avec service précédent en copie
                $mail  =  Mail::to($consultantEmail);
                if ($previousServiceEmail || count($previousServiceEmail) > 0) {
                    $mail->cc($previousServiceEmail);
                }
                $mail->send(new \App\Mail\InvoiceStatusMail($invoice, $messageToConsultant));
            } else {
                // Facture validée → mail au consultant et mail au service suivant
                Mail::to($consultantEmail)
                    ->cc($supervisorEmail)
                    ->send(new \App\Mail\InvoiceStatusMail($invoice, $messageToConsultant));
                if ($nextService) {
                    $nextServiceEmail = User::where('role', $nextService)->pluck('email')->toArray();
                    if (count($nextServiceEmail) > 0) {
                        Mail::to($nextServiceEmail)
                            // ->cc($consultantEmail)
                            ->send(new \App\Mail\InvoiceStatusMail($invoice, $messageToService));
                    }
                }
            }

            return back()->with('success', 'Facture validée par le superviseur.');
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'message' => $th->getMessage()
            ]);
        }
    }
}
