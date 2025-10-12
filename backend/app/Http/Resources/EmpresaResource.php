<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource extends JsonResource
{
    /**
     * Transforma o recurso em array JSON
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'nome'         => $this->nome,
            'cnpj'         => $this->cnpj,
            'endereco'     => $this->endereco,
            'funcionarios' => FuncionarioResource::collection($this->whenLoaded('funcionarios')),
            'clientes'     => ClienteResource::collection($this->whenLoaded('clientes')),
        ];
    }
}
