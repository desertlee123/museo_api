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
        Schema::create('articulos_de_galeria', function (Blueprint $table) {
            $table->foreignId('galerias_id')->constrained('galerias')->onDelete('cascade');
            $table->foreignId('articulos_id')->constrained('articulos')->onDelete('cascade');
            $table->primary(['galerias_id', 'articulos_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_de_galeria');
    }
};
