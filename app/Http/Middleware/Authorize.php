<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authorize as Middleware;

class Authorize extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ability
     * @param  mixed  ...$models
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, $ability, ...$models)
    {
        $this->authorize($ability, $models);
    }
}
