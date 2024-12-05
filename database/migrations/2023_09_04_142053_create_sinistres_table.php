<?php

use App\Enums\SinStatut;
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
        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('famille_id')->constrained('familles')->default(0);
            $table->foreignId('prestataire_id')->constrained('prestataires')->default(0);
            $table->foreignId('membre_id')->constrained('familles_membres');
            $table->foreignId('acte_id')->constrained('actes');
            $table->string('numsin', 100)->nullable()->default('SH');
            $table->dateTime('datsai')->nullable()->default(now());
            $table->dateTime('datmal')->nullable()->default(now());
            $table->string('natact', 100)->nullable()->default('text');
            $table->string('nataff', 100)->nullable()->default('text');
            $table->integer('mnttot')->unsigned()->nullable()->default(12); // Montant total
            $table->integer('mnbase')->unsigned()->nullable()->default(12); //Base humaniis
            $table->integer('mnttmo')->unsigned()->nullable()->default(12); // TM
            $table->integer('mntass')->unsigned()->nullable()->default(12); //Part humaniis
            $table->string('status')->default(SinStatut::A);
            $table->string('attachements', 100)->nullable()->default('text');
            $table->string('testdbal', 100)->nullable()->default('text');
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
        Schema::dropIfExists('sinistres');
    }
};
