<?php

namespace Database\Factories;

use App\Models\Consultant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConsultantFactory extends Factory
{
    protected $model = Consultant::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'resno' => $this->faker->unique()->bothify('RES-###'),
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'nationality' => $this->faker->country,
            'country_of_birth' => $this->faker->country,
            'town_city' => $this->faker->city,
            'date_from' => $this->faker->date(),
            'date_to' => $this->faker->date(),
            'position' => $this->faker->jobTitle,
            'resource_type' => $this->faker->randomElement(['Consultant', 'Intern', 'Contractor']),
            'job_level' => $this->faker->randomElement(['L1', 'L2', 'L3']),

            'supervisor_position' => $this->faker->jobTitle,
            'supervisor' => $this->faker->name(),

            'costc' => $this->faker->bothify('CC-###'),
            'department' => $this->faker->randomElement(['Finance', 'HR', 'IT', 'Research']),
            'dutypost' => $this->faker->city(),

            'original_hire_date' => $this->faker->date(),
            'seniority' => $this->faker->numberBetween(1, 20),
            'birthdate' => $this->faker->date(),
            'nationality_at_birth' => $this->faker->country(),
            'marital_status' => $this->faker->randomElement(['Single', 'Married']),

            'seconded_personnel' => $this->faker->boolean(),
            'shared_working_arrangement' => $this->faker->boolean(),

            'root' => $this->faker->randomElement(['ROOT1', 'ROOT2', 'ROOT3']),
            'division' => $this->faker->randomElement(['Div A', 'Div B']),
            'group' => $this->faker->randomElement(['Group 1', 'Group 2']),
            'cg_unit' => $this->faker->randomElement(['Unit 1', 'Unit 2']),
            'sub_unit' => $this->faker->randomElement(['Sub 1', 'Sub 2']),

            'bg_level' => $this->faker->numerify('BG-#'),
            'cgiar_workforce_group' => $this->faker->randomElement(['Group A', 'Group B']),
            'dutypost_classification' => $this->faker->randomElement(['Class 1', 'Class 2']),
            'education_level' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD']),
            'host_center' => $this->faker->company(),

            'hosted_personnel' => $this->faker->boolean(),
            'hosted_seconded_personnel' => $this->faker->boolean(),

            'secondary_nationality' => $this->faker->country(),
        ];
    }
}
