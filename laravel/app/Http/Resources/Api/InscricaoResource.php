<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InscricaoResource extends JsonResource
{
    /**
     * Contexto: formato público da inscrição — documento mascarado (LGPD), sem e-mail secundário.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $p = $this->pessoa;

        return [
            'id' => $this->id,
            'nome' => $p->nome,
            'nome_social' => $p->nome_social,
            'tipo_pessoa' => $p->tipo_pessoa,
            'documento' => $this->documentoMascarado(), // LGPD
            'email' => $p->email,
            'celular' => $p->celular,
            'whatsapp' => (bool) $p->tem_whatsapp,
            'cidade' => $p->cidade,
            'uf' => $p->uf,
            'distancia' => $this->distancia,
            'categoria' => $this->categoria,
            'equipe' => $this->equipe,
            'origem' => $this->origem,
            'status' => $this->status_cadastro,
            'criado_em' => $this->created_at,
        ];
    }
}
