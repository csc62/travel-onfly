<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // Para API, nunca redireciona, só retorna 401
        return $request->expectsJson() ? null : route('login');
    }
}
