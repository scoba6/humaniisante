<?php

use App\Enums;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     */


    public function up(): void
    {
        Schema::create('familles_membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('famille_id')->constrained('familles')->default(0);
            $table->foreignId('formule_id')->constrained('formules')->default(0);
            $table->foreignId('option_id')->constrained('formules_options')->default(1);
            $table->foreignId('groupe_id')->constrained('groupes');
            $table->string('qualite_id')->constrained('qualites');
            $table->string('nommem')->default('')->nullable(false);
            $table->string('matmem')->default('')->nullable(false);
            $table->date('datnai')->nullable(false);
            $table->integer('agemem')->nullable(false);
            $table->date('valfrm')->nullable(false)->default(now());
            $table->boolean('framem')->nullable()->default(false);
            $table->boolean('optmem')->nullable()->default(false);
            $table->boolean('denmem')->nullable()->default(false);
            $table->string('commem')->default('')->nullable(); //Commentaire
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
        Schema::dropIfExists('familles_membres');
    }
};
