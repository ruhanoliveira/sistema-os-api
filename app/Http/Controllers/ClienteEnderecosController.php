<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteEnderecos;


class ClienteEnderecosController extends Controller
{
    public function create (Request $request,$clientes_id)
    {
        try {
            $clienteEndereco = ClienteEnderecos::create($request->all());
            return response()->json($clienteEndereco,201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function all(Request $request,$clientes_id = null)
    {
        try {
            $metaData = [];
            $requestFilter = formatRequestFilter($request, 'cliente_enderecos.descricao', 'asc', ['descricao' => 'cliente_enderecos.descricao', 'cidade' => 'cidades.nome','cep' => 'cliente_enderecos.cep']);

            $query = ClienteEnderecos::query();

            $query->with('cliente');
            $query->join('clientes','cliente_enderecos.clientes_id','=','clientes.id');

            if(!empty($clientes_id)){
                $query->where('clientes.id','=',$clientes_id);
            }

            $query->with('contato');
            $query->leftJoin('cliente_contatos','cliente_enderecos.cliente_contatos_id','=','cliente_contatos.id');

            $query->with('tipo');
            $query->leftJoin('endereco_tipos','cliente_enderecos.endereco_tipos_id','=','endereco_tipos.id');

            $query->with('cidade', 'cidade.estado');
            $query->leftJoin('cidades','cliente_enderecos.cidades_id','=','cidades.id');

            $query->with('bairro');
            $query->leftJoin('bairros','cliente_enderecos.bairros_id','=','bairros.id');

            foreach($requestFilter['filter'] as $field => $value) {
                switch ($field) {
                    case 'descricao':
                        $value = explode(' ', $value);
                        $value = join('%', $value);
                        $query->where('cliente_enderecos.descricao', 'like', '%' . $value . '%');
                    break;
                    case 'cnpj':
                        $value = explode(' ', $value);
                        $value = join('%', $value);
                        $query->where('cliente_enderecos.cnpj', 'like', '%' . $value . '%');
                    break;
                    case 'cep':
                        $value = explode(' ', $value);
                        $value = join('%', $value);
                        $query->where('cliente_enderecos.cep', 'like', '%' . $value . '%');
                    break;
                    case 'cliente':
                        $query->Where('clientes.id', '=', $value );
                    break;
                    case 'contato':
                        $query->Where('cliente_contatos.id', '=', $value );
                    break;
                    case 'tipo':
                        $query->Where('endereco_tipos.id', '=', $value );
                    break;
                    case 'cidade':
                        $query->Where('cidades.id', '=', $value );
                        break;
                        case 'search':
                        $value = explode(' ', $value);
                        $value = join('%', $value);

                        $query->where(function($query) use ($value){
                            $query
                            ->where('clientes.razao_social', 'like', '%' . $value . '%')
                            ->orWhere('clientes.cnpj', 'like', '%' . $value . '%')
                            ->orWHere('clientes.nome_fantasia', 'like', '%' . $value . '%')
                            ->orWhere('cliente_enderecos.cnpj', 'like', '%' . $value . '%')
                            ->orWhere('cliente_enderecos.cep', 'like', '%' . $value . '%')
                            ->orWhere('cliente_enderecos.descricao', 'like', '%' . $value . '%');
                        });
                        break;
                    }
                }

            $metaData['total'] = $query->count();

            $query->select('cliente_enderecos.*');
            $query->orderBy($requestFilter['sort_by'], $requestFilter['sort_direction']);

            if ($requestFilter['limit'] > 0) {
                $query->offset($requestFilter['offset']);
                $query->limit($requestFilter['limit']);
            }

            $clienteEnderecos = $query->get();

            $result = [
                'data' => $clienteEnderecos,
                'meta' => $metaData
            ];

            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function get($clientes_id = null, $id)
    {
        try {
            $clienteEndereco = ClienteEnderecos::with('cliente')
                ->with('contato')
                ->with('tipo')
                ->with('cidade','cidade.estado')
                ->with('bairro')
                ->find($id);
            return response()->json($clienteEndereco, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function getOnly($id) {
        return $this->get(null, $id);
    }

    public function update(Request $request,$clientes_id, $id)
    {
        try {
            $clienteEndereco = ClienteEnderecos::find($id);
            $clienteEndereco->update($request->all());

            return response()->json($clienteEndereco, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function delete($clientes_id,$id)
    {
        try {
            ClienteEnderecos::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
