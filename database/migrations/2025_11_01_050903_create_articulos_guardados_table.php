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
        Schema::create('articulos_guardados', function (Blueprint $table) {
            $table->foreignId('articulos_id')->constrained('articulos')->onDelete('cascade');
            $table->foreignId('usuarios_id')->constrained('users')->onDelete('cascade');
            $table->primary(['articulos_id', 'usuarios_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_guardados');
    }
};
