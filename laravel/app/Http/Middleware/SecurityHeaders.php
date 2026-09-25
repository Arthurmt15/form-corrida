<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Contexto: injeta headers anti-ataque em toda resposta (clickjacking, sniffing, XSS, referrer).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        // CSP: só arquivos próprios + ViaCEP (busca de endereço).
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; script-src 'self'; style-src 'self'; connect-src 'self' https://viacep.com.br; img-src 'self' data:; object-src 'none'; frame-ancestors 'none'"
        );

        return $response;
    }
}
