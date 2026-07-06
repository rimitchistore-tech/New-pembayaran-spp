<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\HandleCors as Middleware;

class HandleCors extends Middleware
{
    /**
     * The URIs that should be excluded from CORS handling.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
