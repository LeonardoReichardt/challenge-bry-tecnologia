<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ClienteResource;

class ClienteController extends Controller {

    /**
     * Lista todos os clientes já com as empresas relacionadas
     */
    public function index() {
        // Eager Loading: busca clientes + empresas
        $clientes = Cliente::with('empresas')->get();

        // Retorna a lista formatada pelo Resource
        return ClienteResource::collection($clientes);
    }

    /**
     * Cria um novo cliente
     */
    public function store(Request $request) {
        // Validação dos campos
        $validator = Validator::make($request->all(), [
            'login'         => 'required|unique:clientes',
            'nome'          => 'required|string',
            'cpf'           => 'required|unique:clientes',
            'email'         => 'required|email|unique:clientes',
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

        // Se foi enviado um arquivo, salva em storage/app/public/documentos
        if($request->hasFile('documento')) {
            $path = $request->file('documento')->store('documentos', 'public');
            $data['documento'] = $path;
        }

        // Cria o cliente
        $cliente = Cliente::create($data);

        // Associa empresas (se vieram IDs no request)
        if(!empty($data['empresa_ids'])) {
            $cliente->empresas()->sync($data['empresa_ids']);
        }

        // Retorna o cliente criado + empresas associadas
        return new ClienteResource($cliente->load('empresas'));
    }

    /**
     * Lista um cliente específico já com as empresas relacionadas
     */
    public function show($id) {
        $cliente = Cliente::with('empresas')->find($id);

        if(!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        return new ClienteResource($cliente);
    }

    /**
     * Atualiza dados de um cliente
     */
    public function update(Request $request, $id) {
        $cliente = Cliente::find($id);

        if(!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        // Validação dos campos atualizáveis
        $validator = Validator::make($request->all(), [
            'login'         => 'sometimes|string|unique:clientes,login,' . $cliente->id,
            'nome'          => 'sometimes|string',
            'cpf'           => 'sometimes|string|unique:clientes,cpf,' . $cliente->id,
            'email'         => 'sometimes|email|unique:clientes,email,' . $cliente->id,
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
            $path = $request->file('documento')->store('documentos', 'public');
            $data['documento'] = $path;
        }

        // Atualiza os dados
        $cliente->update($data);

        // Se empresas foram enviadas, atualiza o relacionamento
        if(isset($data['empresa_ids'])) {
            $cliente->empresas()->sync($data['empresa_ids']);
        }

        // Retorna o cliente atualizado
        $cliente->refresh();
        return new ClienteResource($cliente->load('empresas'));
    }

    /**
     * Exclui um cliente
     */
    public function destroy($id) {
        $cliente = Cliente::find($id);

        if(!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(['message' => 'Cliente excluído com sucesso']);
    }

}
