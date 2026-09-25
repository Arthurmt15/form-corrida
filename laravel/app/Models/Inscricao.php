<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'pessoa_id', 'distancia', 'tamanho_camiseta', 'categoria',
        'equipe', 'origem', 'status_cadastro', 'aceite_regulamento',
    ];

    protected function casts(): array
    {
        return ['aceite_regulamento' => 'boolean'];
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }

    // LGPD: documento mascarado na exibição.
    public function documentoMascarado(): string
    {
        $n = preg_replace('/\D/', '', $this->pessoa->cpf_cnpj);
        if (strlen($n) === 11) {
            return substr($n, 0, 3).'.***.***-'.substr($n, -2);
        }
        if (strlen($n) === 14) {
            return substr($n, 0, 2).'.***.***/****-'.substr($n, -2);
        }

        return '***';
    }
}
