<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SqlInjectionProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle($request, Closure $next)
    {
        // Obtén los parámetros de consulta de la solicitud
        $queryParams = $request->query();

        // Verifica cada parámetro en busca de caracteres sospechosos
        foreach ($queryParams as $key => $value) {
            if ($this->containsSuspiciousCharacters($value)) {
                
                \Log::warning("Parámetro sospechoso en la consulta: $key=$value");
            }
        }

        return $next($request);
    }

    private function containsSuspiciousCharacters($value)
    {
        // Define una lista de caracteres sospechosos (puedes personalizarla según tus necesidades)
        $suspiciousCharacters = [';', '--', '/*', '*/', 'DROP', 'DELETE', 'UPDATE'];

        // Verifica si el valor contiene alguno de los caracteres sospechosos
        foreach ($suspiciousCharacters as $suspicious) {
            if (stripos($value, $suspicious) !== false) {
                return true;
            }
        }

        return false;
    }
}
