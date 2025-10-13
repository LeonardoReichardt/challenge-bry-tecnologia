<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\FuncionarioResource;

class FuncionarioController extends Controller {

    /**
     * Lista todos os funcionários já com as empresas relacionadas
     */
    public function index() {
        // Eager Loading: busca funcionários + empresas
        $funcionarios = Funcionario::with('empresas')->get();

        // Retorna a lista formatada pelo Resource
        return FuncionarioResource::collection($funcionarios);
    }

    /**
     * Cria um novo funcionário
     */
    public function store(Request $request) {
        // Validação dos campos
        $validator = Validator::make($request->all(), [
            'login'         => 'required|unique:funcionarios',
            'nome'          => 'required|string',
            'cpf'           => 'required|unique:funcionarios',
            'email'         => 'required|email|unique:funcionarios',
            'endereco'      => 'required|string',
            'senha'         => 'required|min:6',
            'documento'     => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120',
            'empresa_ids'   => 'nullable|array',
            'empresa_ids.*' => 'exists:empresas,id'
        ]);

        // Se validação falhar, retorna erro
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Captura os dados validados
        $data = $validator->validated();

        // Criptografa a senha antes de salvar
        $data['senha'] = Hash::make($data['senha']);

        // Se foi enviado um arquivo, salva em storage/app/public/documentos/funcionarios
        if($request->hasFile('documento')) {
            $path = $request->file('documento')->store('documentos/funcionarios', 'public');
            $data['documento'] = $path;
        }

        // Cria o funcionário
        $funcionario = Funcionario::create($data);

        // Associa empresas (se vieram IDs no request)
        if(!empty($data['empresa_ids'])) {
            $funcionario->empresas()->sync($data['empresa_ids']);
        }

        // Retorna o funcionário criado + empresas associadas
        return new FuncionarioResource($funcionario->load('empresas'));
    }

    /**
     * Lista um funcionário específico já com as empresas relacionadas
     */
    public function show($id) {
        $funcionario = Funcionario::with('empresas')->find($id);

        if(!$funcionario) {
            return response()->json(['message' => 'Funcionário não encontrado'], 404);
        }

        return new FuncionarioResource($funcionario);
    }

    /**
     * Atualiza dados de um funcionário
     */
    public function update(Request $request, $id) {
        $funcionario = Funcionario::find($id);

        if(!$funcionario) {
            return response()->json(['message' => 'Funcionário não encontrado'], 404);
        }

        // Validação dos campos atualizáveis
        $validator = Validator::make($request->all(), [
            'login'         => 'sometimes|string|unique:funcionarios,login,' . $funcionario->id,
            'nome'          => 'sometimes|string',
            'cpf'           => 'sometimes|string|unique:funcionarios,cpf,' . $funcionario->id,
            'email'         => 'sometimes|email|unique:funcionarios,email,' . $funcionario->id,
            'endereco'      => 'sometimes|string',
            'senha'         => 'sometimes|min:6',
            'documento'     => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120',
            'empresa_ids'   => 'nullable|array',
            'empresa_ids.*' => 'exists:empresas,id'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Atualiza senha somente se for enviada
        if(isset($data['senha'])) {
            $data['senha'] = Hash::make($data['senha']);
        }

        // Atualiza documento se for enviado
        if($request->hasFile('documento')) {
            $path = $request->file('documento')->store('documentos/funcionarios', 'public');
            $data['documento'] = $path;
        }

        // Atualiza os dados
        $funcionario->update($data);

        // Se empresas foram enviadas, atualiza o relacionamento
        if(isset($data['empresa_ids'])) {
            $funcionario->empresas()->sync($data['empresa_ids']);
        }

        // Retorna o funcionário atualizado
        $funcionario->refresh();
        return new FuncionarioResource($funcionario->load('empresas'));
    }

    /**
     * Exclui um funcionário
     */
    public function destroy($id) {
        $funcionario = Funcionario::find($id);

        if(!$funcionario) {
            return response()->json(['message' => 'Funcionário não encontrado'], 404);
        }

        $funcionario->delete();

        return response()->json(['message' => 'Funcionário excluído com sucesso']);
    }

}
