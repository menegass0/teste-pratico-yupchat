<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if(!auth('api')->user()){
            return response()->json(['success' => false, 'message' => 'Usuário Não Autenticado, realize o login antes de acessar'], 401);
        }

        return $next($request);
    }
}
