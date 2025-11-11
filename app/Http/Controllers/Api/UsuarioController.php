<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Articulo;

class UsuarioController extends Controller
{
    // GET /api/usuarios/articulos/guardados  (protegida -> auth:sanctum)
    public function savedArticles(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Token inválido o expirado'], 401);
            }

            $articulos = $user->articulosGuardados()
                ->select('articulos.id', 'titulo', 'imagen', 'para_socios')
                ->get();

            return response()->json(['articulos' => $articulos], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener los artículos guardados',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /api/usuarios/articulos/guardar  (protegida -> auth:sanctum)
    public function toggleGuardarArticulo(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Token inválido o expirado'], 401);
            }

            // Validar ID del artículo
            try {
                $request->validate([
                    'id_articulo' => 'required|integer|exists:articulos,id',
                ], [
                    'id_articulo.required' => 'Debe especificar el ID del artículo.',
                    'id_articulo.integer' => 'El ID debe ser un número.',
                    'id_articulo.exists' => 'El artículo no existe en el sistema.',
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $e->errors(),
                ], 422);
            }

            $articuloId = $request->id_articulo;

            // Verificar si ya lo tiene guardado
            $yaGuardado = $user->articulosGuardados()->where('articulos_id', $articuloId)->exists();

            if ($yaGuardado) {
                $user->articulosGuardados()->detach($articuloId);
                return response()->json([
                    'message' => 'Artículo eliminado de guardados',
                    'guardado' => false,
                ], 200);
            } else {
                $user->articulosGuardados()->attach($articuloId);
                return response()->json([
                    'message' => 'Artículo guardado correctamente',
                    'guardado' => true,
                ], 201);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error en la operación',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
