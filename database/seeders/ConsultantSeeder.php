<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\BankDetail;
use Illuminate\Database\Seeder;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        Consultant::factory()
            ->count(50)
            ->create()
            ->each(function ($consultant) {
                // attach one bank record
                BankDetail::factory()->create([
                    'consultant_id' => $consultant->id,
                ]);
            });
    }
}
