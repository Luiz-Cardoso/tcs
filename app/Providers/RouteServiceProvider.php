<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * O namespace padrão para os controladores de rotas.
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Caminho para a "home" do seu aplicativo.
     */
    public const HOME = '/home';

    /**
     * Defina as rotas do aplicativo.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Defina as rotas para o aplicativo.
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Rotas API.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Rotas Web.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
