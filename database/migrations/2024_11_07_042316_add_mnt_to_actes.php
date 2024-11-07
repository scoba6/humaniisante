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
        Schema::table('actes', function (Blueprint $table) {
            $table->double('mntact')->nullable(false)->default(0)->after('libact');
            $table->boolean('plafon')->nullable(false)->default(false)->after('mntact'); //Acte plafoné
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actes', function (Blueprint $table) {
            //
        });
    }
};
