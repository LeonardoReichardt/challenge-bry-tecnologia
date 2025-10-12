<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nome', 'cnpj', 'endereco'];

    public function funcionarios() {
        return $this->belongsToMany(Funcionario::class, 'empresa_funcionario');
    }

    public function clientes() {
        return $this->belongsToMany(Cliente::class, 'empresa_cliente');
    }
}
