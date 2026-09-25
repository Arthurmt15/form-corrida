<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InscricaoTest extends TestCase
{
    use RefreshDatabase;

    // Contexto: massa de dados válida; $over sobrescreve campos para cada cenário.
    private function payload(array $over = []): array
    {
        return array_merge([
            'nome' => 'Ana Souza',
            'tipo_pessoa' => 'FISICA',
            'cpf_cnpj' => '529.982.247-25',
            'data_nascimento' => '2000-01-01',
            'email' => 'ana@mail.com',
            'celular' => '(11) 99999-9999',
            'cep' => '01310-100',
            'logradouro' => 'Rua X',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
            'distancia' => '5km',
            'tamanho_camiseta' => 'M',
            'categoria' => 'Geral',
            'origem' => 'Site',
            'status_cadastro' => 'Ativo',
            'aceite_regulamento' => '1',
        ], $over);
    }

    // Contexto: formulário abre com a primeira seção visível.
    public function test_create_exibe_formulario(): void
    {
        $this->get('/inscricoes/create')->assertOk()->assertSee('Dados de Identificação');
    }

    // Contexto: payload válido grava pessoa + inscrição e redireciona ao formulário.
    public function test_store_salva_pessoa_e_inscricao(): void
    {
        $r = $this->post('/inscricoes', $this->payload());
        $r->assertRedirect('/inscricoes/create');
        $this->assertDatabaseHas('pessoas', ['cpf_cnpj' => '529.982.247-25']);
        $this->assertDatabaseHas('inscricoes', ['distancia' => '5km']);
    }

    // Contexto: CPF com dígitos errados volta com erro e nada é gravado.
    public function test_store_rejeita_cpf_invalido(): void
    {
        $this->post('/inscricoes', $this->payload(['cpf_cnpj' => '123.456.789-00']))
            ->assertSessionHasErrors('cpf_cnpj');
    }

    // Contexto: letra no celular volta com erro de validação.
    public function test_store_rejeita_celular_com_letra(): void
    {
        $this->post('/inscricoes', $this->payload(['celular' => '(11) 9999a-9999']))
            ->assertSessionHasErrors('celular');
    }

    // Contexto: mesmo CPF na mesma distância é barrado (UNIQUE) sem duplicar.
    public function test_store_bloqueia_duplicata_mesma_distancia(): void
    {
        $this->post('/inscricoes', $this->payload())->assertRedirect();
        $this->post('/inscricoes', $this->payload(['email' => 'outro@mail.com']))
            ->assertSessionHasErrors('cpf_cnpj');
        $this->assertDatabaseCount('inscricoes', 1);
    }

    // Contexto: API lista em JSON com documento mascarado (LGPD).
    public function test_api_lista_com_documento_mascarado(): void
    {
        $this->post('/inscricoes', $this->payload());
        $j = $this->getJson('/api/inscricoes')->assertOk()->json();
        $this->assertSame(1, $j['meta']['total']);
        $this->assertSame('529.***.***-25', $j['data'][0]['documento']);
    }
}
