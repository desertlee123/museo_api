<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    // GET api/categorias -> Todas las categorías
    public function index()
    {
        $categorias = Categoria::all();

        return response()->json([
            'categorias' => $categorias
        ], 200);
    }
}
