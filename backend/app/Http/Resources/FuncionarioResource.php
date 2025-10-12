<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FuncionarioResource extends JsonResource
{
    /**
     * Transforma o recurso em array JSON
     */
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'login'    => $this->login,
            'nome'     => $this->nome,
            'cpf'      => $this->cpf,
            'email'    => $this->email,
            'endereco' => $this->endereco,
            'documento'=> $this->documento ? url('storage/' . $this->documento) : null,
            'empresas' => EmpresaResource::collection($this->whenLoaded('empresas')),
        ];
    }
}
