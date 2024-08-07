<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Categoria extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $table = 'categorias';
    protected $primaryKey = 'categoria_id';
    public static $rules = [];



    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id', 'categoria_id');
    }
}
