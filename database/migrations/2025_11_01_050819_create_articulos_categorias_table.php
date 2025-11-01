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
        Schema::create('articulos_categorias', function (Blueprint $table) {
            $table->foreignId('categorias_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('articulos_id')->constrained('articulos')->onDelete('cascade');
            $table->primary(['categorias_id', 'articulos_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_categorias');
    }
};
