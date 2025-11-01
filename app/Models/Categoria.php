<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
  use HasFactory;

  protected $fillable = ['nombre', 'imagen'];

  public function articulos()
  {
    return $this->belongsToMany(Articulo::class, 'articulos_categorias', 'categorias_id', 'articulos_id');
  }
}
