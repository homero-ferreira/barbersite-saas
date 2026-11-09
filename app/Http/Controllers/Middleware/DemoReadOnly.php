<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoReadOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->session()->boolean('demo_mode')
            && !in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)
            && $request->path() !== 'painel/sair'
        ) {
            return response()->json([
                'demo_readonly' => true,
                'message' => [
                    'pt' => 'Esta conta de demonstração permite apenas visualizar. Não é possível salvar alterações.',
                    'en' => 'This demo account is for viewing only. Saving changes is not allowed.',
                    'es' => 'Esta cuenta de demostración es solo para visualizar. No se permiten guardar cambios.',
                ],
            ], 403);
        }

        return $next($request);
    }
}