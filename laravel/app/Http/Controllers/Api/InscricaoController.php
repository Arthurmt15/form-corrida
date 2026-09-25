<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\InscricaoResource;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InscricaoController extends Controller
{
    // GET /api/inscricoes?q=&page=&per_page= (máx. 100, anti-abuso).
    // Contexto: lista JSON paginada (20/pág, máx. 100) com busca por nome, e-mail ou cidade.
    public function index(Request $request): AnonymousResourceCollection
    {
        $q = trim($request->query('q', ''));
        $perPage = min(100, max(1, (int) $request->query('per_page', 20)));

        $inscricoes = Inscricao::with('pessoa')
            ->when($q !== '', fn ($qb) => $qb->whereHas('pessoa', fn ($p) => $p
                ->where('nome', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('cidade', 'like', "%{$q}%")))
            ->latest()
            ->paginate($perPage);

        return InscricaoResource::collection($inscricoes);
    }
}
