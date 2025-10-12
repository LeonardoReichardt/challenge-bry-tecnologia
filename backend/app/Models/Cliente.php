<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['login', 'nome', 'cpf', 'email', 'endereco', 'senha', 'documento'];

    protected $hidden = ['senha'];

    public function empresas() {
        return $this->belongsToMany(Empresa::class, 'empresa_cliente');
    }
}
