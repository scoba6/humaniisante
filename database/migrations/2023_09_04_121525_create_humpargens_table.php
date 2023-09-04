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
        Schema::create('humpargens', function (Blueprint $table) {
            $table->id();
            $table->string('CLEPAR', 100)->nullable(false)->default('text'); // Clé du parmètre
            $table->string('LIBPAR', 100)->nullable()->default('text'); // Libelllé du paramètre
            $table->float('TAUVAL')->nullable()->default(123.45); //Valeur ou taux du paramètre
            $table->boolean('ACTIVE')->nullable(false)->default(true);
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
        Schema::dropIfExists('humpargens');
    }
};
