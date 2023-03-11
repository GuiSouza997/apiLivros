<?php

namespace App\Models;

use App\Models\Livro;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Indice extends Model
{
    use HasFactory;

    protected $table = 'indices';
    protected $fillable = ['livro_id', 'indice_pai_id', 'titulo', 'pagina'];
    protected $dates = ['delete_at', 'created_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $primaryKey = 'id';


    public function subIndicesPai()
    {
        return $this->belongsTo(Indice::class, 'id', 'pagina')->select(["id", "titulo", "pagina"]);
    }

}