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
        Schema::create('familles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_id')->constrained('commercials')->default(0);
            $table->string('nomfam')->default('')->nullable(false);
            $table->string('vilfam')->default('')->nullable(); //Ville
            $table->string('payfam')->default('')->nullable(); //Pays
            $table->string('adrfam')->default('')->nullable(); //Adress
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
        Schema::dropIfExists('familles');
    }
};
