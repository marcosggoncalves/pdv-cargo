<?php

use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    /// Usuários
    Route::get('/usuarios', [UsuarioController::class, 'usuarios']);
    Route::post('/usuario', [UsuarioController::class, 'cadastrar']);
    Route::delete('/usuario/{id}', [UsuarioController::class, 'excluir']);
    Route::post('/usuario/{id}', [UsuarioController::class, 'editar']);
    /// Produtos
    Route::get('/produto/{id}', [ProdutoController::class, 'detalhar']);
    Route::get('/produtos-listagem', [ProdutoController::class, 'listagem']);
    Route::get('/produtos', [ProdutoController::class, 'produtos']);
    Route::post('/produto/{id?}', [ProdutoController::class, 'cadastrarEditar']);
    Route::delete('/produto/{id}', [ProdutoController::class, 'excluir']);
    Route::delete('/produto-excluir-imagem/{id}', [ProdutoController::class, 'excluirImagem']);
    /// Pedidos
    Route::get('/pedido/{id}', [PedidoController::class, 'detalhar']);
    Route::get('/pedidos', [PedidoController::class, 'pedidos']);
    Route::post('/relizar-pedido', [PedidoController::class, 'realizarPedidoAdicionarProduto']);
    Route::post('/pedido-adicionar-produto/{id}', [PedidoController::class, 'realizarPedidoAdicionarProduto']);
    Route::delete('/pedido/{id}', [PedidoController::class, 'excluir']);
    Route::delete('/pedido-item/{id}', [PedidoController::class, 'excluirItemPedido']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [UsuarioController::class, 'login']);
    Route::post('/login-check', [UsuarioController::class, 'check']);
});
