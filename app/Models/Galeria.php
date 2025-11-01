<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
  use HasFactory;

  protected $fillable = ['titulo', 'autor', 'descripcion'];

  public function articulos()
  {
    return $this->belongsToMany(Articulo::class, 'articulos_de_galeria', 'galerias_id', 'articulos_id');
  }
}
