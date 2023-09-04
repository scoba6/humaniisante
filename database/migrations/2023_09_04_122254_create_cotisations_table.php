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
        Schema::create('familles_membres_cotisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('familles_membres');
            $table->integer('mntcot')->unsigned()->nullable(false)->default(12);
            $table->integer('mnttps')->unsigned()->nullable(false)->default(12);
            $table->integer('mntcss')->unsigned()->nullable(false)->default(12);
            $table->integer('mntadh')->unsigned()->nullable(false)->default(12);
            $table->integer('mntttc')->unsigned()->nullable(false)->default(12);
            $table->date('datcot')->nullable(false)->default(now());
            $table->date('datval')->nullable()->default(now());
            $table->string('detcot', 100)->nullable()->default('text');
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
        Schema::dropIfExists('familles_membres_cotisations');
    }
};
