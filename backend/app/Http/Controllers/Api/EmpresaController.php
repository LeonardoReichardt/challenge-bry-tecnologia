<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\EmpresaResource;

class EmpresaController extends Controller {

    /**
     * Lista todas as empresas já com funcionários e clientes relacionados
     */
    public function index() {
        // Eager Loading: carrega empresas + relacionamentos
        $empresas = Empresa::with(['funcionarios', 'clientes'])->get();

        // Retorna coleção formatada pelo Resource
        return EmpresaResource::collection($empresas);
    }

    /**
     * Cria uma nova empresa
     */
    public function store(Request $request) {
        // Validação dos campos obrigatórios
        $validator = Validator::make($request->all(), [
            'nome'     => 'required|string|max:255',
            'cnpj'     => 'required|string|unique:empresas,cnpj',
            'endereco' => 'required|string|max:255'
        ]);

        // Se falhar, retorna erro
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cria a empresa com os dados validados
        $empresa = Empresa::create($validator->validated());

        // Retorna a empresa criada no formato Resource
        return new EmpresaResource($empresa);
    }

    /**
     * Lista uma empresa específica já com os funcionários e clientes relacionados
     */
    public function show($id) {
        // Busca empresa pelo ID, já com relacionamentos
        $empresa = Empresa::with(['funcionarios', 'clientes'])->find($id);

        if(!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        return new EmpresaResource($empresa);
    }

    /**
     * Atualiza dados de uma empresa
     */
    public function update(Request $request, $id) {
        $empresa = Empresa::find($id);

        if(!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        // Valida campos enviados
        $validator = Validator::make($request->all(), [
            'nome'     => 'sometimes|string|max:255',
            'cnpj'     => 'sometimes|string|unique:empresas,cnpj,' . $empresa->id,
            'endereco' => 'sometimes|string|max:255'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Atualiza a empresa com os dados validados
        $empresa->update($validator->validated());

        return new EmpresaResource($empresa);
    }

    /**
     * Exclui uma empresa
     */
    public function destroy($id) {
        $empresa = Empresa::find($id);

        if(!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        $empresa->delete();
        return response()->json(['message' => 'Empresa excluída com sucesso']);
    }

}
