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
        Schema::table('familles', function (Blueprint $table) {
            $table->string('statut')->nullable()->default(1)->after('nomfam');
            $table->string('numcdg')->nullable()->default('CDG')->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('familles', function (Blueprint $table) {
            //
        });
    }
};
