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
        Schema::create('sinistre_actes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sinistre_id')->constrained('sinistres')->default(1);
            $table->foreignId('acte_id')->constrained('actes')->default(1);
            $table->integer('qteact')->nullable(false)->default(1);
            $table->double('mntact')->nullable(false)->default(0);
            $table->double('mntxlu')->nullable(false)->default(0);
            $table->double('mnttot')->nullable(false)->default(0);
            $table->double('mnttmo')->nullable(false)->default(0);
            $table->double('mntass')->nullable(false)->default(0);
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
        Schema::dropIfExists('sinistre_actes');
    }
};
