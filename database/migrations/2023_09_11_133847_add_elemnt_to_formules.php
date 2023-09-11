<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Livewire\after;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('formules', function (Blueprint $table) {
            $table->integer('tauamb')->unsigned()->nullable()->default(0)->after('ambfrm');
            $table->integer('tauhos')->unsigned()->nullable()->default(0)->after('tauamb');
            $table->integer('limacc')->unsigned()->nullable()->default(0)->after('tauhos');
            $table->integer('limhos')->unsigned()->nullable()->default(0)->after('limacc');
            $table->integer('limbio')->unsigned()->nullable()->default(0)->after('limhos');
            $table->integer('limrad')->unsigned()->nullable()->default(0)->after('limbio');
            $table->integer('limchr')->unsigned()->nullable()->default(0)->after('limrad');
            $table->integer('limpla')->unsigned()->nullable()->default(0)->after('limchr');
            $table->integer('limact')->unsigned()->nullable()->default(0)->after('limpla');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formules', function (Blueprint $table) {
            //
        });
    }
};
