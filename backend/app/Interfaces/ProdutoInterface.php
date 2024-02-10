<?php

namespace App\Interfaces;

use App\Models\Produto;

use Illuminate\Http\Request;

interface ProdutoInterface
{
    public function listagem();

    public function produtos();

    public function detalhar(int $id);

    public function buscarPorId(int $id);

    public function cadastrarHistorico(Produto $produto,array $historico);

    public function cadastrarEditar(Array $condicaoId, Request $request);

    public function adicionarImagem(Produto $produto, String $url);

    public function excluir(int $id);

    public function excluirImagem(int $id);   
}
