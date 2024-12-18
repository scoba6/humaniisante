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
        Schema::create('reglement_sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reglement_id')->constrained('reglements')->default(1);
            $table->foreignId('sinistre_id')->constrained('sinistres')->default(1);
            $table->integer('mntass')->nullable(false)->default(1);
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
        Schema::dropIfExists('reglement_sinistres');
    }
};
