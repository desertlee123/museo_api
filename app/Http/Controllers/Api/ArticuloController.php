<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Galeria;
use Carbon\Carbon;

class ArticuloController extends Controller
{
    // GET api/articulos -> Todos los artículos
    public function index()
    {
        $articulos = Articulo::with(['metadatos', 'categorias'])
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        return response()->json($articulos, 200);
    }

    // GET api/articulos/recientes -> Últimos 5 artículos
    public function recientes()
    {
        $articulos = Articulo::orderBy('fecha_publicacion', 'desc')
            ->take(5)
            ->get();

        return response()->json($articulos, 200);
    }

    // GET api/articulos/{id} -> Artículo por ID
    public function show($id)
    {
        $articulo = Articulo::with(['metadatos', 'categorias'])
            ->find($id);

        if (!$articulo) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        return response()->json($articulo, 200);
    }

    // GET api/articulos/galeria/{id_galeria} -> Artículos de una galería
    public function porGaleria($id_galeria)
    {
        $galeria = Galeria::with('articulos')->find($id_galeria);

        if (!$galeria) {
            return response()->json(['message' => 'Galería no encontrada'], 404);
        }

        return response()->json($galeria->articulos, 200);
    }

    // GET api/articulos/buscar?titulo=..&autor=..&fecha=..&categoria=..
    public function buscar(Request $request)
    {
        $query = Articulo::query()->with(['metadatos', 'categorias']);

        if ($request->has('titulo')) {
            $query->where('titulo', 'LIKE', '%' . $request->titulo . '%');
        }

        if ($request->has('autor')) {
            $query->where('autor', 'LIKE', '%' . $request->autor . '%');
        }

        if ($request->has('fecha')) {
            $query->whereDate('fecha_publicacion', '=', $request->fecha);
        }

        if ($request->has('categoria')) {
            $query->whereHas('categorias', function ($q) use ($request) {
                $q->where('nombre', 'LIKE', '%' . $request->categoria . '%');
            });
        }

        $articulos = $query->get();

        return response()->json($articulos, 200);
    }
}
