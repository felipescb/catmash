<?php

namespace App\Http\Middleware;

use Closure;

class UpdateDatabase
{
    private $databaseUpdater;

    public function __construct(\App\Services\DatabaseUpdater $databaseUpdater)
    {
        $this->databaseUpdater = $databaseUpdater;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->databaseUpdater->synchronizeMeals();

        return $next($request);
    }
}
