<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // GET /api/usuarios/articulos/guardados  (protegida -> auth:sanctum)
    public function savedArticles(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['message' => 'No autorizado'], 401);

        $articulos = $user->articulosGuardados()
            ->select('articulos.id', 'titulo', 'imagen', 'para_socios')
            ->get();

        return response()->json(['articulos' => $articulos], 200);
    }
}
