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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->string('mensaje', 150);
            $table->enum('estado', ['publicado', 'revision', 'rechazado', 'editado'])->default('revision');
            $table->dateTime('fecha_publicacion')->useCurrent();
            $table->foreignId('articulos_id')->constrained('articulos')->onDelete('cascade');
            $table->foreignId('usuarios_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
