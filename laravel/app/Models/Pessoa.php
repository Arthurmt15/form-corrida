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

    protected function casts(): array
    {
        return ['tem_whatsapp' => 'boolean'];
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }
}
