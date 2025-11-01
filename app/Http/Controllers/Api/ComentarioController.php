<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Articulo;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    // Public: devuelve TODOS los comentarios publicados de un artículo
    // GET /api/comentarios/{articuloId}
    public function indexByArticulo($articuloId)
    {
        $art = Articulo::find($articuloId);
        if (!$art) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        $comentarios = Comentario::where('articulos_id', $articuloId)
            ->where('estado', 'publicado')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comentarios, 200);
    }

    // Admin-only: devuelve comentarios de un usuario en un artículo
    // GET /api/comentarios/{articuloId}/{usuarioId}  (protegida, admin)
    public function indexByArticuloAndUsuario($articuloId, $usuarioId)
    {
        $comentarios = Comentario::where('articulos_id', $articuloId)
            ->where('usuarios_id', $usuarioId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comentarios, 200);
    }

    // Admin-only: búsqueda/filtrado avanzado
    // GET /api/comentarios/buscar?... (protegida, admin)
    public function buscar(Request $request)
    {
        $query = Comentario::query()->with(['usuario', 'articulo']);

        if ($request->filled('id_articulo')) {
            $query->where('articulos_id', $request->id_articulo);
        }

        if ($request->filled('nombre_articulo')) {
            $query->whereHas('articulo', function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->nombre_articulo . '%');
            });
        }

        if ($request->filled('id_usuario')) {
            $query->where('usuarios_id', $request->id_usuario);
        }

        if ($request->filled('nombre_usuario')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nombre_usuario . '%');
            });
        }

        if ($request->filled('mail_usuario')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->mail_usuario . '%');
            });
        }

        if ($request->filled('socio')) {
            // espera 'true' o 'false' (o 'partner' / 'user')
            $val = $request->socio === 'true' ? 'partner' : $request->socio;
            $query->whereHas('usuario', function ($q) use ($val) {
                $q->where('role', $val);
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        if ($request->filled('mensaje')) {
            $query->where('mensaje', 'like', '%' . $request->mensaje . '%');
        }

        $result = $query->orderBy('created_at', 'desc')->get();

        if ($result->isEmpty()) {
            return response()->json(['message' => 'No se encontraron resultados'], 404);
        }

        return response()->json(['comentarios' => $result], 200);
    }

    // Usuario autenticado crea comentario
    // POST /api/usuarios/comentar  (protegida -> auth:sanctum)
    public function storeUserComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'articulos_id' => 'required|integer|exists:articulos,id',
            'mensaje' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        $comentario = Comentario::create([
            'mensaje' => $request->mensaje,
            // fecha_publicacion la maneja DB con timestamps
            'estado' => 'revision',
            'articulos_id' => $request->articulos_id,
            'usuarios_id' => $user->id,
        ]);

        return response()->json($comentario, 201);
    }

    // Admin: actualizar o eliminar comentario
    // POST /api/comentarios  (protegida -> auth:sanctum)
    // body: { operation: "update"|"delete", id_comentario: int, mensaje?:string, estado?:string }
    public function adminModify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operation' => 'required|in:update,delete',
            'id_comentario' => 'required|integer|exists:comentarios,id',
            'mensaje' => 'nullable|string|max:1000',
            'estado' => 'nullable|in:publicado,revision,rechazado,editado',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comentario = Comentario::find($request->id_comentario);
        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado'], 404);
        }

        if ($request->operation === 'delete') {
            $comentario->delete();
            return response()->json(['message' => 'Comentario eliminado'], 200);
        }

        // update
        if ($request->filled('mensaje')) {
            $comentario->mensaje = $request->mensaje;
        }
        if ($request->filled('estado')) {
            $comentario->estado = $request->estado;
        }
        // Si se edita por admin, opcional marcar 'editado'
        if ($request->operation === 'update' && $request->filled('mensaje') && $comentario->estado === 'publicado') {
            // podés decidir si marcás el estado; lo dejamos opcional
        }

        $comentario->save();

        return response()->json($comentario, 200);
    }
}
