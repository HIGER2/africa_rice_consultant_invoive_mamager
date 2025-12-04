<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class InitialSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('/staff.xlsx');
        // public_path()
        $rows = Excel::toArray([], $path)[1];
        // dump($rows);

        // dump("=== Contenu du fichier Excel ===");

        $this->initData($rows);
        // dump($rows[2]);
    }
    public function initData($rows)
    {
        $payload = [];
        foreach (array_slice($rows, 0, 46) as $key => $row) {
            if ($key == 0 || $key == 1) continue;
            // --- Consultant payload ---
            $parts = explode(',', $row[3]);

            $consultantPayload = [
                "resno" => $row[2],
                "name" => $parts[0],
                "last_name" => isset($parts[1]) ? trim($parts[1]) : null,
                "gender" => strtolower($row[4]),
                "nationality" => strtolower($row[5]),
                "country_of_birth" => strtolower($row[7]),
                "town_city" => strtolower($row[8]),
                "date_from" => $this->excelDate($row[9]),
                "date_to" => $this->excelDate($row[10]),
                "position" => strtolower($row[12]),
                "resource_type" => strtolower($row[14]),
                "job_level" => strtolower($row[15]),
                "costc" => strtolower($row[21]),
                "department" => strtolower($row[22]),
                "dutypost" => strtolower($row[23]),
                "original_hire_date" => $this->excelDate($row[24]),
                "seniority" => $row[25],
                "birthdate" => $this->excelDate($row[26]),
                "nationality_at_birth" => strtolower($row[28]),
                "marital_status" => strtolower($row[29]),
                "seconded_personnel" => strtolower($row[31]) == 'no' ? false : true,
                "shared_working_arrangement" => strtolower($row[32]) == 'no' ? false : true,
                "root" => strtolower($row[34]),
                "division" => strtolower($row[36]),
                "group" => strtolower($row[38]),
                "cg_unit" => strtolower($row[40]),
                "sub_unit" => strtolower($row[42]),
                "bg_level" => strtolower($row[43]),
                "cgiar_workforce_group" => strtolower($row[44]),
                "dutypost_classification" => strtolower($row[45]),
                "education_level" => strtolower($row[46]),
                "host_center" => strtolower($row[47]),
                "hosted_personnel" => strtolower($row[48]) == 'no' ? false : true,
                "hosted_seconded_personnel" => strtolower($row[49]) == 'no' ? false : true,
                "secondary_nationality" => strtolower($row[50]),
            ];

            // --- Supervisor payload ---
            $partsSuper = explode(',', $row[19]);

            $supervisorPayload = [
                "resno" => $row[18],
                "name" => isset($partsSuper[0]) ? trim($partsSuper[0]) : null,
                "last_name" => isset($partsSuper[1]) ? trim($partsSuper[1]) : null,
                "position" => strtolower($row[17]),
            ];

            // dump($consultantPayload);
            // continue;
            // Check employee table to get supervisor email
            $employee = Employee::firstWhere('matricule', $supervisorPayload['resno']);

            if ($employee) {
                $supervisorPayload['email'] = $employee->email;
                $supervisorPayload['name'] = $employee->firstName;
                $supervisorPayload['last_name'] = $employee->lastName;
            }
            // SAVE supervisor correctly
            $supervisor = Supervisor::updateOrCreate(
                ['resno' => $supervisorPayload['resno']],
                $supervisorPayload
            );


            // Now attach supervisor_id to consultant
            $consultantPayload['supervisor_id'] = $supervisor->id ?? null;

            // Save consultant
            // Check employee table to get supervisor email
            $employeeConsultant = Employee::firstWhere('matricule', $consultantPayload['resno']);
            if ($employeeConsultant) {
                $consultantPayload['email'] = $employeeConsultant->email;
            }
            $consultant = Consultant::updateOrCreate(
                ['resno' => $consultantPayload['resno']],
                $consultantPayload
            );

            // Create/update consultant user
            User::updateOrCreate(
                ['consultant_id' => $consultant->id],
                [
                    'name' => $consultant->name,
                    'last_name' => $consultant->last_name,
                    'email' => $consultant->email ?? null,
                    'role' => 'consultant',
                    'consultant_id' => $consultant->id,
                ]
            );

            // Create/update supervisor user
            $supervisorPayload['role'] = "supervisor";
            $supervisorPayload['supervisor_id'] = $supervisor->id;

            unset($supervisorPayload['resno']);
            unset($supervisorPayload['position']);
            dump("superviseur mail", $supervisorPayload ?? '');
            User::updateOrCreate(
                ['supervisor_id' => $supervisor->id],
                $supervisorPayload
            );

            // sleep(1);
            // dump($consultant->id);
        }
    }


    function excelDate($value)
    {
        if (!$value || !is_numeric($value)) {
            return null;
        }

        return \Carbon\Carbon::createFromDate(1899, 12, 30)
            ->addDays($value)
            ->format('Y-m-d');
    }
}
