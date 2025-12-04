<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'resno' => $this->resno,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'institution' => $this->institution,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'country_of_birth' => $this->country_of_birth,
            'town_city' => $this->town_city,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'position' => $this->position,
            'resource_type' => $this->resource_type,
            'job_level' => $this->job_level,


            'supervisor' => $this->supervisor->name . ' ' . $this->supervisor->last_name,
            'supervisor_resno' => $this->supervisor->resno,
            'supervisor_position' => $this->supervisor->position,

            'costc' => $this->costc,
            'department' => $this->department,
            'dutypost' => $this->dutypost,
            'original_hire_date' => $this->original_hire_date,
            'seniority' => $this->seniority,
            'birthdate' => $this->birthdate,
            'nationality_at_birth' => $this->nationality_at_birth,
            'marital_status' => $this->marital_status,
            'seconded_personnel' => (bool) $this->seconded_personnel,
            'shared_working_arrangement' => (bool) $this->shared_working_arrangement,
            'root' => $this->root,
            'division' => $this->division,
            'group' => $this->group,
            'cg_unit' => $this->cg_unit,
            'sub_unit' => $this->sub_unit,
            'bg_level' => $this->bg_level,
            'cgiar_workforce_group' => $this->cgiar_workforce_group,
            'dutypost_classification' => $this->dutypost_classification,
            'education_level' => $this->education_level,
            'host_center' => $this->host_center,
            'hosted_personnel' => (bool) $this->hosted_personnel,
            'hosted_seconded_personnel' => (bool) $this->hosted_seconded_personnel,
            'secondary_nationality' => $this->secondary_nationality,
            'bank_name' => optional($this->bankDetails)->bank_name ?? null,
            'iban' => optional($this->bankDetails)->iban ?? null,
            'swift_code' => optional($this->bankDetails)->swift_code ?? null,
            // 'bank_details' => $this->bankDetails->map(fn($b) => [
            //     'id' => $b->id,

            // ]),
            // 'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
