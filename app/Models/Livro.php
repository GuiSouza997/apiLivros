<?php

namespace App\Models;

// // use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Models\Indice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'livros';
    protected $fillable = ['usuario_publicador_id', 'titulo'];
    protected $dates = ['delete_at', 'created_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $primaryKey = 'id';


    protected $casts = [
        'titulo' => 'string',
        'id' => 'integer',
    ];

    public function indices()
    {
        return $this->belongsTo(Indice::class, 'id')->select(["id", "titulo", "pagina"]);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'usuario_publicador_id')->select(["id", "name"]);
    }

}