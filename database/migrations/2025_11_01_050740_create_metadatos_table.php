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
        Schema::create('metadatos', function (Blueprint $table) {
            $table->id();
            $table->string('autor', 45)->nullable();
            $table->string('editor', 45)->nullable();
            $table->string('proveedor_de_datos', 45)->nullable();
            $table->date('fecha_creacion')->nullable();
            $table->string('pais_proveedor', 45)->nullable();
            $table->date('ultima_actualizacion_de_proveedor')->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadatos');
    }
};
