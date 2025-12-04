<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultantRequest;
use App\Http\Resources\ConsultantDetailResource;
use App\Http\Resources\ConsultantInvoice;
use App\Http\Resources\ConsultantResource;
use App\Models\BankDetail;
use App\Models\Consultant;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsultantController extends Controller
{
    public function index(Request $request)
    {
        $query = Consultant::with('bankDetails', 'supervisor')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('resno', 'LIKE', "%$search%");
            });
        }
        // Use resource collection and keep pagination structure
        $consultants = $query->get();
        $data = ConsultantResource::collection($consultants);
        return Inertia::render('views/Consultant', [
            'consultants' => $data,
            'filters' => [
                'search' => $request->search,
            ],
            'urls' => [
                'create' => route('consultants.create'),
                'index'  => route('consultants.index'),
            ],
        ]);
    }

    public function innvoice($uuid)
    {
        $consultants = Consultant::with(['bankDetails', 'supervisor', 'invoices'])
            ->where('uuid', $uuid)->first();

        // Use resource collection and keep pagination structure
        $data = new ConsultantInvoice($consultants);
        return Inertia::render('views/ConsultantInvoice', [
            'consultant' => $data,
            'urls' => [
                'create' => route('consultants.create'),
                'index'  => route('consultants.index'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('views/ConsultantForm', [
            'consultant' => null,
            'isEdit' => false,
            'actionUrl' => route('consultants.store'),
            'cancelUrl' => route('consultants.index'),
        ]);
    }

    public function store(ConsultantRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['uuid'] = (string) Str::uuid();
            // Log incoming bank data and supervisor email for debugging
            // Log::info('ConsultantController@store incoming data', [
            //     'supervisor_email' => $data['supervisor_email'] ?? null,
            // ]);
            // // Look up supervisor by email
            // if (!empty($data['supervisor_email'])) {
            //     $supervisor = Employee::where('email', $data['supervisor_email'])->first();
            //     if (!$supervisor) {
            //         return redirect()->back()->withErrors([
            //             'supervisor_email' => "The supervisor with email {$data['supervisor_email']} does not exist."
            //         ])->withInput();
            //     }
            //     $data['supervisor_id'] = $supervisor->id;
            // }
            // verify bank data
            $bank = BankDetail::where('iban', $data['iban'])->first();
            if ($bank) {
                return redirect()->back()->withErrors([
                    'iban' => "The IBAN {$data['iban']} is already associated with another bank detail."
                ])->withInput();
            }

            // Extract bank data
            $bankData = [
                'bank_name' => $data['bank_name'] ?? null,
                'iban' => $data['iban'] ?? null,
                'swift_code' => $data['swift_code'] ?? null,
            ];

            // Log incoming bank data and supervisor email for debugging
            Log::info('ConsultantController@store incoming data', [
                'supervisor_email' => $data['supervisor_email'] ?? null,
                'bankData' => $bankData,
            ]);

            // Create consultant without bank data and supervisor_email
            // creer le compt du superviseur s'il n'existe pas

            // Log::info('ConsultantController@store supervisor user check', [
            //     'supervisor_email' => $data['supervisor.email'] ?? null,
            //     'supervisorUserExists' => $supervisorUser ? true : false,
            // ]);  

            $supervisorUser = Supervisor::firstOrCreate(
                ['email' => $data['supervisor']['email']], // condition
                [
                    ...$data['supervisor'],
                ]
            );

            unset($data['bank_name'], $data['iban'], $data['swift_code'], $data['supervisor']);

            $data['supervisor_id'] = $supervisorUser->id;
            $consultant = Consultant::create($data);
            // Create bank data
            if (!empty($bankData['iban']) || !empty($bankData['bank_name'])) {
                $consultant->bankDetails()->create($bankData);
            }
            // creer user auth 
            if ($consultant->email_cgiar) {
                User::create(
                    [
                        'name' => $consultant->name,
                        'last_name' => $consultant->last_name,
                        'email' => $consultant->email_cgiar,
                        'role' => 'consultant',
                        'consultant_id' => $consultant->id,
                        // 'password' => bcrypt(Str::random(12)), // mot de passe temporaire
                        // 'uuid' => (string) Str::uuid(),
                    ]
                );
            }

            // creer les superviseur
            if (!$supervisorUser->user) {
                $supervisorUser->user()->create(
                    [
                        'name' => $supervisorUser->name,
                        'last_name' => $supervisorUser->last_name,
                        'email' => $supervisorUser->email,
                        'role' => 'supervisor',
                        'supervisor_id' => $supervisorUser->id,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('consultants.index')->with('success', 'Consultant created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error creating consultant: ' . $th->getMessage(), ['stack' => $th->getTraceAsString()]);
            return redirect()->back()->with('error', 'An error occurred while creating the consultant. Please try again.');
            //throw $th;
        }
    }

    public function show(string $uuid)
    {
        $consultant = Consultant::where('uuid', $uuid)->with('bankDetails')->firstOrFail();
        return Inertia::render('Consultants/Show', [
            'consultant' => new ConsultantResource($consultant)
        ]);
    }

    public function edit(string $uuid)
    {
        $consultant = Consultant::where('uuid', $uuid)->with('bankDetails', 'supervisor')->firstOrFail();
        return Inertia::render('views/ConsultantForm', [
            'consultant' => new ConsultantDetailResource($consultant),
            'isEdit' => true,
            'actionUrl' => route('consultants.update', $uuid),
            'cancelUrl' => route('consultants.index'),
        ]);
    }

    public function update(ConsultantRequest $request, string $consultant)
    {
        $consultant = Consultant::where('uuid', $consultant)->firstOrFail();
        $data = $request->validated();

        // // Look up supervisor by email
        // if (!empty($data['supervisor_email'])) {
        //     $supervisor = Employee::where('email', $data['supervisor_email'])->first();
        //     if (!$supervisor) {
        //         return redirect()->back()->withErrors([
        //             'supervisor_email' => "The supervisor with email {$data['supervisor_email']} does not exist."
        //         ])->withInput();
        //     }
        //     $data['supervisor_id'] = $supervisor->id;
        // }

        // Extract bank data
        $bankData = [
            'bank_name' => $data['bank_name'] ?? null,
            'iban' => $data['iban'] ?? null,
            'swift_code' => $data['swift_code'] ?? null,
        ];

        // Log incoming bank data and supervisor email for debugging
        Log::info('ConsultantController@update incoming data', [
            'supervisor_email' => $data['supervisor_email'] ?? null,
            'bankData' => $bankData,
        ]);

        $supervisorUser = Supervisor::firstOrCreate(
            ['email' => $data['supervisor']['email']], // condition
            [
                ...$data['supervisor'],
            ]
        );

        // Update consultant without bank data and supervisor_email
        unset($data['bank_name'], $data['iban'], $data['swift_code'], $data['supervisor_email']);
        $data["supervisor_id"] = $supervisorUser->id;
        $consultant->update($data);

        // Update or create bank data
        $bank = $consultant->bankDetails()->first();
        // update Or create user Account 
        // creer user auth 
        if ($consultant->email_cgiar) {

            $consultant->user()->updateOrcreate(
                ['consultant_id' => $consultant->id],
                [
                    'name' => $consultant->name,
                    'last_name' => $consultant->last_name,
                    'email' => $consultant->email_cgiar,
                    'role' => 'consultant',
                    'consultant_id' => $consultant->id,
                ]
            );
        }
        if ($bank) {
            $bank->update($bankData);
        } elseif (!empty($bankData['iban']) || !empty($bankData['bank_name'])) {
            $consultant->bankDetails()->create($bankData);
        }

        return redirect()->route('consultants.index')->with('success', 'Consultant updated successfully.');
    }

    public function destroy(string $uuid)
    {
        $consultant = Consultant::where('uuid', $uuid)->firstOrFail();
        $consultant->delete();

        return back()->with('success', 'Consultant deleted successfully.');
    }

    public function exportCsv()
    {
        $fileName = 'consultants_export_' . now()->format('YmdHis') . '.csv';

        $consultants = Consultant::with('bankDetails')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'uuid',
            'resno',
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
            'supervisor_position',
            'supervisor',
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

            'bank_name',
            'iban',
            'swift_code'
        ];

        $callback = function () use ($consultants, $columns) {
            $file = fopen('php://output', 'w');

            // export header
            fputcsv($file, $columns);

            foreach ($consultants as $c) {
                $bank = $c->bankDetails->first();

                fputcsv($file, [
                    $c->uuid,
                    $c->resno,
                    $c->name,
                    $c->last_name,
                    $c->gender,
                    $c->nationality,
                    $c->country_of_birth,
                    $c->town_city,
                    $c->date_from,
                    $c->date_to,
                    $c->position,
                    $c->resource_type,
                    $c->job_level,
                    $c->supervisor_position,
                    $c->supervisor,
                    $c->costc,
                    $c->department,
                    $c->dutypost,
                    $c->original_hire_date,
                    $c->seniority,
                    $c->birthdate,
                    $c->nationality_at_birth,
                    $c->marital_status,
                    $c->seconded_personnel,
                    $c->shared_working_arrangement,
                    $c->root,
                    $c->division,
                    $c->group,
                    $c->cg_unit,
                    $c->sub_unit,
                    $c->bg_level,
                    $c->cgiar_workforce_group,
                    $c->dutypost_classification,
                    $c->education_level,
                    $c->host_center,
                    $c->hosted_personnel,
                    $c->hosted_seconded_personnel,
                    $c->secondary_nationality,

                    $bank?->bank_name,
                    $bank?->iban,
                    $bank?->swift_code,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
