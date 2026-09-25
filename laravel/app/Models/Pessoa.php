<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pessoa extends Model
{
    protected $table = 'pessoas';

    protected $fillable = [
        'nome', 'nome_social', 'tipo_pessoa', 'cpf_cnpj', 'rg_ie',
        'data_nascimento', 'genero', 'email', 'email2', 'celular',
        'tem_whatsapp', 'telefone_fixo', 'cep', 'logradouro', 'numero',
        'complemento', 'bairro', 'cidade', 'uf',
    ];

    // Contexto: converte tem_whatsapp (0/1 do banco) para boolean no PHP.
    protected function casts(): array
    {
        return ['tem_whatsapp' => 'boolean'];
    }

    // Contexto: uma pessoa pode ter várias inscrições (ex: 5km e 10km).
    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }
}
