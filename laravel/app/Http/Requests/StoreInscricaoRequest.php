<?php

namespace App\Http\Requests;

use App\Rules\Celular;
use App\Rules\CpfCnpj;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInscricaoRequest extends FormRequest
{
    // Contexto: formulário público — qualquer visitante pode se inscrever (sem login).
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Contexto: regras das 4 seções do formulário (tipos, tamanhos, listas fechadas, dígitos).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

        return [
            // Seção 1 — identificação
            'nome' => 'required|string|max:150',
            'nome_social' => 'nullable|string|max:150',
            'tipo_pessoa' => 'required|in:FISICA,JURIDICA',
            'cpf_cnpj' => ['required', 'string', 'max:18', new CpfCnpj],
            'rg_ie' => 'nullable|string|max:20',
            'data_nascimento' => 'required|date|before_or_equal:today',
            'genero' => 'nullable|string|max:20',
            // Seção 2 — contato (só números, com DDD)
            'email' => 'required|email|max:150',
            'email2' => 'nullable|email|max:150',
            'celular' => ['required', 'string', 'max:20', new Celular],
            'tem_whatsapp' => 'sometimes|boolean',
            'telefone_fixo' => ['nullable', 'string', 'max:20', new Celular],
            // Seção 3 — endereço
            'cep' => ['required', 'string', 'max:9', 'regex:/^\d{5}-?\d{3}$/'],
            'logradouro' => 'required|string|max:150',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'uf' => 'required|string|size:2|in:'.implode(',', $ufs),
            // Seção 4 — corrida
            'distancia' => 'required|in:5km,10km,21km,42km',
            'tamanho_camiseta' => 'required|in:PP,P,M,G,GG',
            'categoria' => 'required|string|max:50',
            'equipe' => 'nullable|string|max:100',
            'origem' => 'required|string|max:50',
            'status_cadastro' => 'required|in:Ativo,Inativo,Bloqueado',
            'aceite_regulamento' => 'accepted',
        ];
    }

    // Contexto: mensagens em PT-BR para as regras que precisam de texto amigável.
    public function messages(): array
    {
        return ['aceite_regulamento.accepted' => 'É preciso aceitar o regulamento.'];
    }
}
