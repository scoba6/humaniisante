<?php

use App\Enums\ModReg;
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
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestataire_id')->constrained('prestataires')->default(0);
            $table->string('numreg')->nullable(false)->default('reg');
            $table->double('mntreg')->nullable(false)->default(0);
            $table->boolean('estclo')->nullable(false)->default(0);
            $table->string('modreg')->default(ModReg::A);
            $table->string('attachements')->default('attachements');
            $table->string('comment')->nullable()->default('comment');
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
        Schema::dropIfExists('reglements');
    }
};
