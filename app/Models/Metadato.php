<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadato extends Model
{
  use HasFactory;

  protected $table = 'metadatos';

  protected $fillable = [
    'autor',
    'editor',
    'proveedor_de_datos',
    'fecha_creacion',
    'pais_proveedor',
    'ultima_actualizacion_de_proveedor',
    'descripcion'
  ];

  public function articulo()
  {
    return $this->hasOne(Articulo::class, 'metadatos_id');
  }
}
