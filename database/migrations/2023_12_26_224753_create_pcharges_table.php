<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pcharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestataire_id')->constrained('prestataires')->default(0);
            $table->foreignId('famille_id')->constrained('familles')->default(0);
            $table->foreignId('membre_id')->constrained('familles_membres');
            $table->foreignId('redac_id')->constrained('users');// Redacteur
            $table->foreignId('ctrler_id')->constrained('users'); // Controleur
            $table->string('numpch')->nullable(false)->default('numpch')->unique(); // N° de prise en charge
            $table->dateTime('datemi')->nullable(false)->default(now());
            $table->dateTime('datexp')->nullable(false)->default(now());
            $table->string('qrcpch')->unique()->nullable(); //qr code
            $table->boolean('isvalid')->nullable(false)->default(true); //Tag de validité de la PC
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcharges');
    }
};
