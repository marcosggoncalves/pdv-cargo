<?php

namespace App\Repositories;

use App\Interfaces\ProdutoInterface;
use App\Models\Produto;
use App\Models\ProdutoImagens;

class ProdutoRepository implements ProdutoInterface
{
    public function listagem()
    {
        return Produto::orderBy('descricao')->get();
    }

    public function buscarPorId(int $id)
    {
        return Produto::findOrFail($id);
    }

    public function produtos()
    {
        return Produto::orderBy('descricao')->paginate(15);
    }

    public function detalhar(int $id)
    {
        return Produto::with(['historico', 'imagens'])->findOrFail($id);
    }

    public function cadastrarHistorico($produto, $historio)
    {
        return  $produto->historico()->create($historio);
    }

    public function cadastrarEditar($condicaoId, $body)
    {
        return Produto::updateOrCreate($condicaoId, $body);
    }

    public function adicionarImagem($produto, $url)
    {
        return $produto->imagens()->create(['url' => $url]);
    }

    public function excluir(int $id)
    {
        $produto = Produto::with(['historico', 'imagens'])->findOrFail($id);

        $produto->imagens()->delete();

        $produto->historico()->delete();

        $produto->delete();

        return  $produto;
    }

    public function excluirImagem(int $id)
    {
        $imagem = ProdutoImagens::findOrFail($id);

        $imagem->delete();

        return  $imagem;
    }
}
