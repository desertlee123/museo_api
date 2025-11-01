<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeria;
use App\Models\Articulo;

class GaleriaController extends Controller
{
    // GET /api/galerias?limit=3
    public function index(Request $request)
    {
        /* $limit = $request->query('limit');
        $query = Galeria::select('id', 'titulo', 'descripcion', 'autor', 'created_at');

        if ($limit) {
            $galerias = $query->take($limit)->get();
        } else {
            $galerias = $query->get();
        }

        return response()->json($galerias); */

        $limit = $request->query('limit');

        // Incluimos los artículos para sacar la imagen de portada
        $query = Galeria::with(['articulos' => function ($q) {
            $q->select('articulos.id', 'articulos.imagen');
        }])->select('id', 'titulo', 'descripcion', 'autor', 'created_at');

        $galerias = $limit ? $query->take($limit)->get() : $query->get();

        // Agregamos la imagen de portada (primer artículo)
        $galerias->transform(function ($galeria) {
            $galeria->imagen = $galeria->articulos->first()->imagen ?? null;
            unset($galeria->articulos); // opcional, para no incluir todo el array
            return $galeria;
        });

        return response()->json($galerias);
    }

    // GET /api/galerias/{id}
    public function show($id)
    {
        $galeria = Galeria::with(['articulos' => function ($q) {
            $q->select('articulos.id', 'titulo', 'imagen', 'para_socios');
        }])->find($id);

        if (!$galeria) {
            return response()->json(['message' => 'Galería no encontrada'], 404);
        }

        return response()->json($galeria);
    }

    // GET /api/galerias/buscar?titulo=...&autor=...&fecha=...
    public function buscar(Request $request)
    {
        $titulo = $request->query('titulo');
        $autor = $request->query('autor');
        $fecha = $request->query('fecha');

        $query = Galeria::query();

        if ($titulo) {
            $query->where('titulo', 'like', "%$titulo%");
        }
        if ($autor) {
            $query->where('autor', 'like', "%$autor%");
        }
        if ($fecha) {
            $query->whereDate('created_at', $fecha);
        }

        $result = $query->get();

        if ($result->isEmpty()) {
            return response()->json(['message' => 'No se encontraron resultados'], 404);
        }

        return response()->json($result);
    }
}
