<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use App\Models\Livro;
use App\Models\Indice;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Auth;

class LivroController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        if ($request->has('titulo')) {
            $titulo = $request->titulo;

            $livros = Livro::with(['users', 'indices'])->where('livros.titulo', 'ILIKE', '%' . $titulo . '%');

        } else if ($request->has('titulo_do_indice')) {

            $tituloIndice = $request->titulo_do_indice;

            $livros = Livro::with([
                'users',
                'indices' => function ($query) use ($tituloIndice) {
                    $query->where('titulo', 'ILIKE', '%' . $tituloIndice . '%');
                    $query->select("id", "titulo", "pagina");
                    return $query;
                }
            ]);

        } else {
            $livros = Livro::with(['users', 'indices']);
        }

        $livros = $livros->get()->map(function ($livro) {

            if ($livro->users) {
                $livro->usuario_publicador = $livro->users;
                // $livro->usuario_publicador->nome = $livro->users->name;
                if ($livro->usuario_publicador) {
                    $livro->usuario_publicador->nome = $livro->usuario_publicador->name;
                    unset($livro->usuario_publicador->name);
                }
            }

            if ($livro->indices) {

                $livro->indices->subindices = $livro->indices->with('subIndicesPai')->get();

                if ($livro->indices->subindices) {
                    unset($livro->indices->subindices->sub_indices_pai);
                }

            }

            // if ($livro->indices->subindices) {
            //     unset($livro->indices->subindices->livro_id);
            //     unset($livro->indices->subindices->indice_pai_id);
            //     unset($livro->indices->subindices->created_at);
            //     unset($livro->indices->subindices->updated_at);
            //     unset($livro->indices->subindices->delete_at);
            // }
            unset($livro->users);
            unset($livros->id);
            unset($livros->usuario_publicador_id);
            unset($livros->created_at);
            unset($livros->updated_at);
            unset($livros->delete_at);

            return $livro;
        });


        if ($livros->count()) {
            return response()->json(['success' => true, 'livros' => $livros], $this->successStatus);
        } else {
            return response()->json(['success' => true, 'message' => "Não foram encontrado livros nesta busca.", 'livros' => $livros], $this->successStatus);
        }
    }


    public function create(Request $request)
    {
        if ($request->has('titulo')) {

            if ($request->has('indices')) {

                $userid = Auth::user()->id;

                $livro = new Livro;
                $livro->usuario_publicador_id = $userid;
                $livro->titulo = $request->titulo;

                if ($livro->save()) {
                    $dataMessage = $this->validateFields($request->indices, ["titulo", "pagina"], true);
                    $livroId = $livro->id;
                    if (is_array($dataMessage) and empty($dataMessage)) {

                        collect($request->indices)->map(function ($item) use ($livroId) {

                            $indice = new Indice;
                            $indice->livro_id = $livroId;
                            $indice->titulo = $item["titulo"];
                            $indice->pagina = $item["pagina"];
                            $indice->indice_pai_id = $item["pagina"];
                            // Salvando indice
                            $indice->save();

                        });
                        return response()->json(['success' => true, "message" => "Livro criado com sucesso."], $this->successStatus);
                    }
                }
            } else {
                return response()->json(['success' => false, "message" => "O índice do livro, deve ser preenchido."], $this->successStatus);
            }
        } else {
            return response()->json(['success' => false, "message" => "O título do livro, deve ser preenchido."], $this->successStatus);
        }

    }

    public function validateFields($dataArray, $keyValidate = [], $isIndice = false)
    {
        $arrayMessageErrors = [];
        if (gettype($dataArray) == "array" and !empty($dataArray)) {
            foreach ($keyValidate as $key) {
                # code...
                foreach ($dataArray as $indice) {
                    # code...
                    if (!isset($indice[$key])) {
                        $arrayMessageErrors["success"] = false;
                        if ($isIndice) {
                            $arrayMessageErrors["message"] = "O valor do campo '{$key}' para o índice, não pode ser vazio ou nulo.";
                        } else {
                            $arrayMessageErrors["message"] = "O valor do campo '{$key}' para o sub-índice, não pode ser vazio ou nulo.";
                        }
                    } else if ($indice[$key] == null) {
                        $arrayMessageErrors["error"] = true;
                        if ($isIndice) {
                            $arrayMessageErrors["message"] = "O valor do campo '{$key}' para o índice, não pode ser vazio ou nulo.";
                        } else {
                            $arrayMessageErrors["message"] = "O valor do campo '{$key}' para o sub-índice, não pode ser vazio ou nulo.";
                        }
                    }
                }
            }
        }

        return $arrayMessageErrors;
    }


    public function importarIndicesXml($id)
    {
        $livro = Livro::with(["users", "indices"])->find($id);

        $xmlData = "";
        if ($livro) {
            $xmlData .= '<item id="' . $livro->id . '" titulo="' . $livro->titulo . '" />';
            foreach ($livro->indices() as $indice) {
                $xmlData .= '<indice>';
                if ($indice) {
                    $xmlData .= '<item pagina="' . $indice->pagina . '" titulo="' . $indice->titulo . '">';
                    foreach ($indice->subindices() as $subindices) {
                        if ($subindices) {
                            $xmlData .= '<item pagina="' . $subindices->pagina . '" titulo="' . $subindices->titulo . '" />';
                        }
                    }
                    $xmlData .= '</item>';
                }
                $xmlData .= '</indice>';
            }
        }

        return response()->view('xml.index', [
            'xmlData' => $xmlData
        ])->header('Content-Type', 'text/xml');
    }

}