<?php

namespace App\Interfaces;

use App\Models\Pedido;

interface PedidoInterface
{
    public function buscarPorId(int $id);

    public function buscarPedidoProduto(int $pedidoId, int $produtoId);

    public function pedidos();

    public function detalhar(int $id);

    public function cadastrar();

    public function excluir(int $id);

    public function adicionarItemPedido(Pedido $pedido, int $produtoID, int $quantidade);

    public function excluirItemPedido(int $id);
}
