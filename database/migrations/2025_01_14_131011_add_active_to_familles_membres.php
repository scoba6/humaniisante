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
        Schema::table('familles_membres', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('commem');
            $table->date('datret')->nullable(true)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('familles_membres', function (Blueprint $table) {
            //
        });
    }
};
