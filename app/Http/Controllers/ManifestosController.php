<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manifestos;

class ManifestosController extends Controller
{
    public function create (Request $request)
    {
        try {
            $params = $request->all();
            $params['data_geracao'] = $params['data_geracao'] ? $params['data_geracao'] : date('d/m/Y H:i');

            $ordemServico = Manifestos::create($params);

            return response()->json($ordemServico,201);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function all(Request $request)
    {
        try {
            $metaData = [];
            $requestFilter = formatRequestFilter($request, 'manifestos.id', 'desc', [
                'id' => 'manifestos.id',
                'data_geracao' => 'manifestos.data_geracao',
                'cliente_nome' => 'clientes.razao_social'
            ]);

            $query = Manifestos::query();

            $query->with('cliente');
            $query->leftJoin('clientes','manifestos.clientes_id','=','clientes.id');

            $query->with('gerador', 'gerador.cliente');
            $query->leftJoin('cliente_enderecos','manifestos.gerador_id','=','cliente_enderecos.id');

            $query->with('receptor');
            $query->leftJoin('receptores','manifestos.receptores_id','=','receptores.id');

            $query->with('veiculo');
            $query->leftJoin('veiculos','manifestos.veiculos_id','=','veiculos.id');

            $query->with('residuo');
            $query->leftJoin('residuos','manifestos.residuos_id','=','residuos.id');

            foreach($requestFilter['filter'] as $field => $value) {
                switch ($field) {
                    case 'id':
                        $query->where('manifestos.id', '=', $value);
                    break;
                    case 'data_inicio':
                        $value = date('Y-m-d 00:00:00', strtotime(\str_replace('/','-',$value)));
                        $query->where('manifestos.data_geracao', '>=', $value );
                    break;
                    case 'data_fim':
                        $value = date('Y-m-d 23:59:59', strtotime(\str_replace('/','-',$value)));
                        $query->where('manifestos.data_geracao', '<=', $value );
                    break;
                    case 'data':
                        $value = date('Y-m-d H:i:00', strtotime(\str_replace('/','-',$value)));
                        $query->where('manifestos.data_geracao', '=', $value );
                    break;
                    case 'cliente_id':
                        $query->where('clientes.id', '=', $value);
                    break;
                    case 'cliente_nome':
                        $value = explode(' ', $value);
                        $value = join('%', $value);
                        $query->where('clientes.razao_social', 'like', '%' . $value . '%');
                    break;
                    case 'cliente_cnpj':
                        $value = explode(' ', $value);
                        $value = join('%', $value);
                        $query->where('clientes.cnpj', 'like', '%' . $value . '%');
                    break;
                }
            }

            $metaData['total'] = $query->count();

            $query->select('manifestos.*');
            $query->orderBy($requestFilter['sort_by'], $requestFilter['sort_direction']);
            $query->offset($requestFilter['offset']);
            $query->limit($requestFilter['limit']);

            $manifestos = $query->get();

            $result = [
                'data' => $manifestos,
                'meta' => $metaData
            ];

            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function get($id)
    {
        try {
            $ordemServico = Manifestos::
                with('cliente')
                ->with('gerador')
                ->with('receptor')
                ->find($id);
            return response()->json($ordemServico, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $ordemSrvico = Manifestos::find($id);
            $ordemSrvico->update($request->all());

            return response()->json($ordemSrvico, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function delete($id)
    {
        try {
            Manifestos::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
