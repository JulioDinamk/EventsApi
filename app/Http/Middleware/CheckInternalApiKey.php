<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInternalApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('INTERNAL_PROVISIONING_KEY');
        $providedKey = $request->header('X-INTERNAL-API-KEY');

        if (empty($providedKey) || $providedKey !== $expectedKey) {

            return response()->json([
                'message' => 'Acesso negado. Chave de API interna inválida ou ausente.'
            ], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
