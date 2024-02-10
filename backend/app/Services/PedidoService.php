<?php

namespace App\Services;

use App\Repositories\PedidoRepository;
use App\Repositories\ProdutoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoService
{
    protected $repository;

    protected $repositoryProduto;

    public function __construct(PedidoRepository $repository, ProdutoRepository $repositoryProduto)
    {
        $this->repository = $repository;

        $this->repositoryProduto = $repositoryProduto;
    }

    private function verificarProdutoPedido($pedidoID, $produto)
    {
        $produtoPedidoVerificar = $this->repository->buscarPedidoProduto($pedidoID, $produto['produto_id']);

        if ($produtoPedidoVerificar) {

            $produtoPedidoVerificar->quantidade += $produto['quantidade'];

            return  $produtoPedidoVerificar->save();
        }

        return false;
    }

    public function pedidos()
    {
        return $this->repository->pedidos();
    }

    public function detalhar(int $id)
    {
        return $this->repository->detalhar($id);
    }

    public function realizarPedidoAdicionarProduto(Request $request, ?string $id = null)
    {
        $body = $request->all();

        if (!isset($body['produtos']) || count($body['produtos'])  == 0) {
            throw new \Exception('Nenhum produto foi selecionado, monte sua lista!');
        }

        try {
            DB::beginTransaction();

            $pedido = isset($id) ? $this->repository->buscarPorId($id) : $this->repository->cadastrar($body);

            if (!$pedido) {
                throw new \Exception('Não foi possivel abrir pedido!');
            }

            foreach ($body['produtos'] as $item) {
                if ($item['quantidade'] <= 0) {
                    throw new \Exception("Informe uma quantidade valida, quantidade precisa ser maior que 0.");
                }

                $produto = $this->repositoryProduto->buscarPorId($item['produto_id']);

                if (!$produto) {
                    throw new \Exception("Produto não encontrado: {$produto->descricao}");
                }

                if ($produto->estoque < $item['quantidade']) {
                    throw new \Exception("Produto fora do estoque: {$produto->descricao}");
                }

                $itemPedido = $this->verificarProdutoPedido($pedido->id, $item);

                if (!$itemPedido) {
                    $this->repository->adicionarItemPedido($pedido, $item['produto_id'],  $item['quantidade']);
                }

                $this->repositoryProduto->cadastrarHistorico($produto, ['tipo' => 'Saida', 'quantidade' => $item['quantidade']]);

                $pedido->total += ($produto->valor_venda * $item['quantidade']);

                $produto->estoque -= $item['quantidade'];

                $produto->save();
            }

            $pedido->save();

            DB::commit();

            return $pedido;
        } catch (\Exception $e) {

            DB::rollBack();
            
            throw $e;
        }
    }

    public function excluir(int $id)
    {
        $pedido = $this->repository->excluir($id);

        if (!$pedido) {
            throw new \Exception('Não foi possivel excluir pedido!');
        }

        if (isset($pedido->produtos)) {
            foreach ($pedido->produtos as $item) {

                $produto = $this->repositoryProduto->buscarPorId($item->id);

                if (!$produto) {
                    throw new \Exception("Produto não encontrado: {$produto->descricao}");
                }

                $pedido->produtos()->detach($item->id);

                $this->repositoryProduto->cadastrarHistorico($produto, ['tipo' => 'Entrada', 'quantidade' => $item->pivot->quantidade]);

                $produto->estoque += $item->pivot->quantidade;

                $produto->save();
            }
        }

        return $pedido;
    }

    public function excluirItemPedido(int $id)
    {
        $item = $this->repository->excluirItemPedido($id);

        $pedido = $this->repository->buscarPorId($item->pedido_id);

        if (!$pedido) {
            throw new \Exception('Pedido não foi lecalizado pedido!');
        }

        $produto = $this->repositoryProduto->buscarPorId($item->produto_id);

        $this->repositoryProduto->cadastrarHistorico($produto, ['tipo' => 'Entrada', 'quantidade' => $item->quantidade]);

        $produto->estoque += $item->quantidade;

        $produto->save();

        $pedido->total -= ($produto->valor_venda * $item['quantidade']);

        $pedido->save();

        return $item;
    }
}
