<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Funcionario::with('empresas')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'login'     => 'required|unique:funcionarios',
            'nome'      => 'required',
            'cpf'       => 'required|unique:funcionarios',
            'email'     => 'required|email|unique:funcionarios',
            'endereco'  => 'required',
            'senha'     => 'required',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg|max:2048'
        ]);

        if($request->hasFile('documento')) {
            $data['documento'] = $request->file('documento')->store('documentos', 'public');
        }

        $funcionario = Funcionario::create($data);

        return response()->json($funcionario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Funcionario::with('empresas')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update($request->all());
        return response()->json($funcionario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Funcionario::destroy($id);
        return response()->noContent();
    }
}
