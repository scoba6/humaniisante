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
        Schema::table('sinistre_actes', function (Blueprint $table) {
            $table->integer('natact')->nullable(false)->default(1)->after('acte_id');
            $table->integer('nataff')->nullable(false)->default(1)->after('natact');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sinistre_actes', function (Blueprint $table) {
            //
        });
    }
};
