<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
  use HasFactory;

  protected $fillable = [
    'autor',
    'titulo',
    'descripcion',
    'fecha_publicacion',
    'imagen',
    'para_socios',
    'metadatos_id'
  ];

  public function metadatos()
  {
    return $this->belongsTo(Metadato::class, 'metadatos_id');
  }

  public function categorias()
  {
    return $this->belongsToMany(Categoria::class, 'articulos_categorias', 'articulos_id', 'categorias_id');
  }

  public function galerias()
  {
    return $this->belongsToMany(Galeria::class, 'articulos_de_galeria', 'articulos_id', 'galerias_id');
  }

  public function comentarios()
  {
    return $this->hasMany(Comentario::class, 'articulos_id');
  }

  public function usuariosQueGuardaron()
  {
    return $this->belongsToMany(User::class, 'articulos_guardados', 'articulos_id', 'usuarios_id');
  }
}
