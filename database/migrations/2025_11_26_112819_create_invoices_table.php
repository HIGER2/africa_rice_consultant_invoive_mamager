<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            // Relation
            $table->foreignId('consultant_id')->nullable()->constrained()->onDelete('set null');
            $table->string('invoice_number')->unique();
            // Localisation & contrat
            $table->string('location')->nullable();
            $table->date('contract_period_from')->nullable();
            $table->date('contract_period_to')->nullable();
            $table->date('date')->nullable();
            // Forfaitaire
            $table->boolean('is_forfaitaire_contract')->default(false);
            $table->decimal('forfaitaire_amount', 12, 2)->nullable();

            // Honoraires
            $table->decimal('honoraires_mensuel', 12, 2)->nullable();
            $table->integer('jours_travailles')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->decimal('honoraires_a_payer', 12, 2)->nullable();

            $table->enum('status', [
                'pending_supervisor',   // en attente de validation du superviseur
                'pending_hr',           // validé par le superviseur, attente RH
                'pending_finance',      // validé par RH, attente finances
                'approved',             // tout validé
                'rejected'              // rejeté à n'importe quelle étape
            ])->default('pending_supervisor');

            // Documents
            $table->boolean('rapport_activite_required')->default(false);
            $table->string('rapport_activite_file')->nullable();

            $table->boolean('clearance_required')->default(false);
            $table->string('clearance_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
