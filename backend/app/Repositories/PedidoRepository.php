<?php

namespace App\Repositories;

use App\Interfaces\PedidoInterface;
use App\Models\Pedido;
use App\Models\PedidoProduto;

class PedidoRepository implements PedidoInterface
{
    public function buscarPorId(?int $id)
    {
        return Pedido::findOrFail($id);
    }

    public function buscarPedidoProduto($pedidoId, $produtoId)
    {
        return PedidoProduto::where('pedido_id', $pedidoId)->where('produto_id', $produtoId)->first();
    }

    public function pedidos()
    {
        return Pedido::orderBy('id', 'desc')->paginate(15);
    }

    public function detalhar(int $id)
    {
        return Pedido::with(['produtos'])->findOrFail($id);
    }

    public function cadastrar()
    {
        return Pedido::create(['situacao' => 'Aberto']);
    }

    public function excluir(int $id)
    {
        $pedido = Pedido::with('produtos')->findOrFail($id);

        $pedido->produtos()->detach();

        $pedido->delete();

        return $pedido;
    }

    public function excluirItemPedido(int $id)
    {
        $pedidoItem = PedidoProduto::findOrFail($id);

        $pedidoItem->delete();

        return $pedidoItem;
    }

    public function adicionarItemPedido($pedido, $produtoID, $quantidade)
    {
        return $pedido->produtos()->attach($produtoID, ['quantidade' =>  $quantidade]);;
    }
}
