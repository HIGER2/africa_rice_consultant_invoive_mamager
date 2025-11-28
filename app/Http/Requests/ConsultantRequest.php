<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resno' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:50'],

            'phone' => ['nullable', 'string', 'unique:consultants,phone,' . $this->route('consultant'), 'max:50'],
            'email' => ['required', 'email', 'unique:consultants,email,' . $this->route('consultant'), 'max:50'],
            'email_cgiar' => ['nullable', 'email', 'unique:consultants,email_cgiar,' . $this->route('consultant'), 'max:50'],

            'nationality' => ['required', 'string', 'max:255'],
            'country_of_birth' => ['required', 'string', 'max:255'],
            'town_city' => ['required', 'string', 'max:255'],

            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],

            'position' => ['required', 'string', 'max:255'],
            'resource_type' => ['required', 'string', 'max:255'],
            'job_level' => ['required', 'string', 'max:255'],

            // 'supervisor_email' => ['required', 'string', 'email', 'max:255'],

            'costc' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'dutypost' => ['required', 'string', 'max:255'],

            'original_hire_date' => ['required', 'date'],
            'seniority' => ['required', 'integer'],

            'birthdate' => ['required', 'date'],

            'nationality_at_birth' => ['required', 'string', 'max:255'],
            'marital_status' => ['required', 'string', 'max:255'],

            'seconded_personnel' => ['required', 'boolean'],
            'shared_working_arrangement' => ['required', 'boolean'],

            'root' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'cg_unit' => ['required', 'string', 'max:255'],
            'sub_unit' => ['required', 'string', 'max:255'],

            'bg_level' => ['required', 'string', 'max:255'],
            'cgiar_workforce_group' => ['required', 'string', 'max:255'],

            'dutypost_classification' => ['required', 'string', 'max:255'],
            'education_level' => ['required', 'string', 'max:255'],

            'host_center' => ['required', 'string', 'max:255'],
            'hosted_personnel' => ['required', 'boolean'],
            'hosted_seconded_personnel' => ['required', 'boolean'],
            'secondary_nationality' => ['required', 'string', 'max:255'],

            // bank
            'bank_name' => ['required', 'string', 'max:150'],
            'iban' => ['required', 'string', 'max:34'],
            'swift_code' => ['required', 'string', 'max:11'],
            // supervisor
            'supervisor.name' => ['required', 'string', 'max:255'],
            'supervisor.last_name' => ['required', 'string', 'max:255'],
            'supervisor.position' => ['required', 'string', 'max:255'],
            'supervisor.email' => ['required', 'email', 'max:255', 'unique:supervisors,email'],
            'supervisor.resno' => ['required', 'string', 'max:255', 'unique:supervisors,resno'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name is too long (max 255 characters).',
            'resno.max' => 'The resource number is too long.',
            'last_name.max' => 'The last name is too long.',
            'gender.max' => 'The gender value is too long.',

            'nationality.max' => 'The nationality is too long.',
            'country_of_birth.max' => 'The country of birth is too long.',
            'town_city.max' => 'The town/city is too long.',

            'date_from.date' => 'The start date must be a valid date.',
            'date_to.date' => 'The end date must be a valid date.',
            'date_to.after_or_equal' => 'The end date must be after or equal to the start date.',

            'position.max' => 'The position is too long.',
            'resource_type.max' => 'The resource type is too long.',
            'job_level.max' => 'The job level is too long.',

            'supervisor_position.max' => 'The supervisor position is too long.',
            'supervisor.max' => 'The supervisor name is too long.',

            'costc.max' => 'The cost center is too long.',
            'department.max' => 'The department name is too long.',
            'dutypost.max' => 'The duty post is too long.',

            'original_hire_date.date' => 'The hire date must be a valid date.',
            'seniority.integer' => 'The seniority must be an integer.',

            'birthdate.date' => 'The birth date must be a valid date.',

            'nationality_at_birth.max' => 'The nationality at birth is too long.',
            'marital_status.max' => 'The marital status is too long.',

            'seconded_personnel.boolean' => 'Seconded personnel must be true or false.',
            'shared_working_arrangement.boolean' => 'Shared working arrangement must be true or false.',

            'root.max' => 'Root is too long.',
            'division.max' => 'Division is too long.',
            'group.max' => 'Group is too long.',
            'cg_unit.max' => 'CG unit is too long.',
            'sub_unit.max' => 'Sub unit is too long.',

            'bg_level.max' => 'BG level is too long.',
            'cgiar_workforce_group.max' => 'CGIAR workforce group is too long.',

            'dutypost_classification.max' => 'Duty post classification is too long.',
            'education_level.max' => 'Education level is too long.',

            'host_center.max' => 'Host center is too long.',
            'hosted_personnel.boolean' => 'Hosted personnel must be true or false.',
            'hosted_seconded_personnel.boolean' => 'Hosted/Seconded personnel must be true or false.',

            'secondary_nationality.max' => 'The secondary nationality is too long.',


            // bank messages
            'bank_name.max' => 'The bank name is too long.',
            'iban.max' => 'The IBAN is too long.',
            'swift_code.max' => 'The SWIFT code is too long.',
        ];
    }
}
