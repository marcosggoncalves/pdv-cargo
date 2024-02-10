<?php

namespace App\Providers;

use App\Interfaces\ProdutoInterface;
use App\Interfaces\UsuarioInterface;
use App\Interfaces\PedidoInterface;
use App\Repositories\ProdutoRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UsuarioInterface::class, UsuarioRepository::class);
        $this->app->bind(ProdutoInterface::class, ProdutoRepository::class);
        $this->app->bind(PedidoInterface::class, PedidoRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
