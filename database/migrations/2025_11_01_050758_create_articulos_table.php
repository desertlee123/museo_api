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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('autor', 45)->nullable();
            $table->string('titulo', 100);
            $table->text('descripcion')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->string('imagen', 100)->nullable();
            $table->boolean('para_socios')->default(false);
            $table->foreignId('metadatos_id')->constrained('metadatos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
