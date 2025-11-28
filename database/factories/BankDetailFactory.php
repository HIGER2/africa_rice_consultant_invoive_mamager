<?php

namespace Database\Factories;

use App\Models\BankDetail;
use App\Models\Consultant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankDetailFactory extends Factory
{
    protected $model = BankDetail::class;

    public function definition(): array
    {
        return [
            'consultant_id' => Consultant::factory(),
            'bank_name' => $this->faker->company . ' Bank',
            'iban' => $this->faker->iban(),
            'swift_code' => strtoupper($this->faker->bothify('????###')),
        ];
    }
}
