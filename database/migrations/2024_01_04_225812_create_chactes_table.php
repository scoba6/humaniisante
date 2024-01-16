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
        Schema::create('chactes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charge_id')->constrained('charges')->default(0);
            $table->foreignId('acte_id')->constrained('actes')->default(0);
            $table->integer('qteact')->unsigned()->nullable()->default(1);
            $table->integer('mntact')->unsigned()->nullable()->default(0);
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
        Schema::dropIfExists('chactes');
    }
};
