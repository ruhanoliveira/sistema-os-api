<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversas;

class ConversasController extends Controller
{
    public function create (Request $request, $clientes_id)
    {
        try {
            $conversa = Conversas::create($request->all());
            return response()->json($conversa,201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function all(Request $request, $clientes_id)
    {
        try {
            $metaData = [];
            $requestFilter = formatRequestFilter($request, 'conversas.data', 'desc', ['funcionario' => 'funcionarios.nome']);

            $query = Conversas::query();
            $query->with('cliente');
            $query->join('clientes','conversas.clientes_id','=','clientes.id');

            $query->with('funcionario');
            $query->join('funcionarios','conversas.funcionarios_id','=','funcionarios.id');

            $query->with('acao');
            $query->join('conversa_acoes','conversas.conversa_acoes_id','=','conversa_acoes.id');

            foreach($requestFilter['filter'] as $field => $value) {
                switch ($field) {
                    case 'data':
                        $query->where('conversas.data', '=', $value);
                    break;
                    case 'funcionario':
                        $query->Where('funcionarios.id', '=', $value );
                    break;

                    case 'acao':
                        $query->Where('conversa_acoes.id', '=', $value );
                    break;
                }
            }

            $metaData['total'] = $query->count();

            $query->select('conversas.*');
            $query->orderBy($requestFilter['sort_by'], $requestFilter['sort_direction']);
            $query->offset($requestFilter['offset']);
            $query->limit($requestFilter['limit']);

            $conversas = $query->get();

            $result = [
                'data' => $conversas,
                'meta' => $metaData
            ];

            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function get($clientes_id, $id)
    {
        try {
            $conversa = Conversas::with('cliente')->with('funcionario')->with('acao')->find($id);
            return response()->json($conversa, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function update(Request $request, $clientes_id, $id)
    {
        try {
            $conversa = Conversas::find($id);
            $conversa->update($request->all());

            return response()->json($conversa, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function delete($clientes_id, $id)
    {
        try {
            Conversas::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
