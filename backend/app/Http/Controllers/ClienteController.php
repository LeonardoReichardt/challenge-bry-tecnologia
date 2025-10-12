<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cliente::with('empresas')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'login'     => 'required|unique:clientes',
            'nome'      => 'required',
            'cpf'       => 'required|unique:clientes',
            'email'     => 'required|email|unique:clientes',
            'endereco'  => 'required',
            'senha'     => 'required',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg|max:2048'
        ]);

        if($request->hasFile('documento')) {
            $data['documento'] = $request->file('documento')->store('documentos', 'public');
        }

        $cliente = Cliente::create($data);

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Cliente::with('empresas')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return response()->json($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Cliente::destroy($id);
        return response()->noContent();
    }
}
