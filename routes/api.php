<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function (){
    return 'API Funcionando';
});

Route::get('estados', 'EstadosController@all');
Route::post('estados', 'EstadosController@create');
Route::get('estados/{id}/cidades', 'CidadesController@listByEstado');

Route::get('cidades', 'CidadesController@all');
Route::post('cidades', 'CidadesController@create');
Route::get('cidades/{id}', 'CidadesController@get');
Route::put('cidades/{id}', 'CidadesController@update');
Route::delete('cidades/{id}', 'CidadesController@delete');
Route::get('cidades/{id}/bairros', 'BairrosController@listByCidade');

Route::get('bairros', 'BairrosController@all');
Route::post('bairros', 'BairrosController@create');
Route::get('bairros/{id}', 'BairrosController@get');
Route::put('bairros/{id}', 'BairrosController@update');
Route::delete('bairros/{id}', 'BairrosController@delete');

Route::get('servicos', 'ServicosController@all');
Route::post('servicos', 'ServicosController@create');
Route::get('servicos/{id}', 'ServicosController@get');
Route::put('servicos/{id}', 'ServicosController@update');
Route::delete('servicos/{id}', 'ServicosController@delete');

Route::get('funcionario-cargos', 'FuncionarioCargosController@all');
Route::post('funcionario-cargos', 'FuncionarioCargosController@create');
Route::get('funcionario-cargos/{id}', 'FuncionarioCargosController@get');
Route::put('funcionario-cargos/{id}', 'FuncionarioCargosController@update');
Route::delete('funcionario-cargos/{id}', 'FuncionarioCargosController@delete');

Route::get('funcionarios', 'FuncionariosController@all');
Route::post('funcionarios', 'FuncionariosController@create');
Route::get('funcionarios/{id}', 'FuncionariosController@get');
Route::put('funcionarios/{id}', 'FuncionariosController@update');
Route::delete('funcionarios/{id}', 'FuncionariosController@delete');

Route::get('cliente-atividades', 'ClienteAtividadesController@all');
Route::post('cliente-atividades', 'ClienteAtividadesController@create');
Route::get('cliente-atividades/{id}', 'ClienteAtividadesController@get');
Route::put('cliente-atividades/{id}', 'ClienteAtividadesController@update');
Route::delete('cliente-atividades/{id}', 'ClienteAtividadesController@delete');

Route::get('clientes', 'ClientesController@all');
Route::post('clientes', 'ClientesController@create');
Route::get('clientes/{id}', 'ClientesController@get');
Route::put('clientes/{id}', 'ClientesController@update');
Route::delete('clientes/{id}', 'ClientesController@delete');

Route::get('conversa-acoes', 'ConversaAcoesController@all');
Route::post('conversa-acoes', 'ConversaAcoesController@create');
Route::get('conversa-acoes/{id}', 'ConversaAcoesController@get');
Route::put('conversa-acoes/{id}', 'ConversaAcoesController@update');
Route::delete('conversa-acoes/{id}', 'ConversaAcoesController@delete');

Route::get('clientes/{clientes_id}/conversas', 'ConversasController@all');
Route::post('clientes/{clientes_id}/conversas', 'ConversasController@create');
Route::get('clientes/{clientes_id}/conversas/{id}', 'ConversasController@get');
Route::put('clientes/{clientes_id}/conversas/{id}', 'ConversasController@update');
Route::delete('clientes/{clientes_id}/conversas/{id}', 'ConversasController@delete');

Route::get('clientes/{clientes_id}/cobrancas', 'ClienteCobrancasController@all');
Route::post('clientes/{clientes_id}/cobrancas', 'ClienteCobrancasController@create');
Route::get('clientes/{clientes_id}/cobrancas/{id}', 'ClienteCobrancasController@get');
Route::put('clientes/{clientes_id}/cobrancas/{id}', 'ClienteCobrancasController@update');
Route::delete('clientes/{clientes_id}/cobrancas/{id}', 'ClienteCobrancasController@delete');

Route::get('clientes/{clientes_id}/contatos', 'ClienteContatosController@all');
Route::post('clientes/{clientes_id}/contatos', 'ClienteContatosController@create');
Route::get('clientes/{clientes_id}/contatos/{id}', 'ClienteContatosController@get');
Route::put('clientes/{clientes_id}/contatos/{id}', 'ClienteContatosController@update');
Route::delete('clientes/{clientes_id}/contatos/{id}', 'ClienteContatosController@delete');

Route::get('clientes/{clientes_id}/enderecos', 'ClienteEnderecosController@all');
Route::post('clientes/{clientes_id}/enderecos', 'ClienteEnderecosController@create');
Route::get('clientes/{clientes_id}/enderecos/{id}', 'ClienteEnderecosController@get');
Route::put('clientes/{clientes_id}/enderecos/{id}', 'ClienteEnderecosController@update');
Route::delete('clientes/{clientes_id}/enderecos/{id}', 'ClienteEnderecosController@delete');

Route::get('endereco-tipos', 'EnderecoTiposController@all');
Route::post('endereco-tipos', 'EnderecoTiposController@create');
Route::get('endereco-tipos/{id}', 'EnderecoTiposController@get');
Route::put('endereco-tipos/{id}', 'EnderecoTiposController@update');
Route::delete('endereco-tipos/{id}', 'EnderecoTiposController@delete');
