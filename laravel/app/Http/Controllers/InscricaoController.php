<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInscricaoRequest;
use App\Models\Inscricao;
use App\Models\Pessoa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    // Contexto: lista paginada (20/pág) com busca por nome, e-mail ou cidade da pessoa.
    public function index(Request $request): View
    {
        $q = trim($request->query('q', ''));
        $inscricoes = Inscricao::with('pessoa')
            ->when($q !== '', fn ($qb) => $qb->whereHas('pessoa', fn ($p) => $p
                ->where('nome', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('cidade', 'like', "%{$q}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('inscricoes.index', compact('inscricoes', 'q'));
    }

    // Contexto: exibe o formulário em branco (4 seções via partials Blade).
    public function create(): View
    {
        return view('inscricoes.create');
    }

    // Contexto: grava pessoa (reaproveita por CPF) + inscrição em transação; duplicata volta com erro amigável.
    public function store(StoreInscricaoRequest $request): RedirectResponse
    {
        // Honeypot anti-bot: preenchido = finge sucesso sem salvar.
        if ($request->filled('site_url')) {
            return redirect()->route('inscricoes.create')->with('sucesso', true);
        }

        $v = $request->validated();

        try {
            $id = DB::transaction(function () use ($v, $request) {
                $pessoa = Pessoa::firstOrCreate(
                    ['cpf_cnpj' => $v['cpf_cnpj']],
                    collect($v)->only([
                        'nome', 'nome_social', 'tipo_pessoa', 'rg_ie', 'data_nascimento',
                        'genero', 'email', 'email2', 'celular', 'telefone_fixo', 'cep',
                        'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
                    ])->merge(['tem_whatsapp' => $request->boolean('tem_whatsapp')])->all()
                );

                return $pessoa->inscricoes()->create([
                    'distancia' => $v['distancia'],
                    'tamanho_camiseta' => $v['tamanho_camiseta'],
                    'categoria' => $v['categoria'],
                    'equipe' => $v['equipe'] ?? null,
                    'origem' => $v['origem'],
                    'status_cadastro' => $v['status_cadastro'],
                    'aceite_regulamento' => true,
                ])->id;
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // UNIQUE(pessoa_id, distancia) — já inscrito nesta prova.
            return back()->withErrors(['cpf_cnpj' => 'Este CPF/CNPJ já está inscrito nesta distância.'])->withInput();
        }

        return redirect()->route('inscricoes.create')->with('sucesso_id', $id);
    }
}
