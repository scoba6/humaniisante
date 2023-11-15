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
        Schema::table('familles_membres_cotisations', function (Blueprint $table) {
            $table->integer('mntmen')->unsigned()->nullable(false)->default(12)->after('mntacc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('familles_membres_cotisations', function (Blueprint $table) {
            //
        });
    }
};
