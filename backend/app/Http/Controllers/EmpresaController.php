<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Empresa::with(['funcionarios', 'clientes'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required',
            'cnpj'     => 'required|unique:empresas',
            'endereco' => 'required'
        ]);

        $empresa = Empresa::create($data);

        return response()->json($empresa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Empresa::with(['funcionarios', 'clientes'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        return response()->json($empresa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Empresa::destroy($id);
        return response()->noContent();
    }
}
