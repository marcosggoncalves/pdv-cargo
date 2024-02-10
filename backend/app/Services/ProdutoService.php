<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdutoService
{
    protected $repository;

    public function __construct(ProdutoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listagem()
    {
        return $this->repository->listagem();
    }

    public function produtos()
    {
        return $this->repository->produtos();
    }

    public function detalhar(int $id)
    {
        return $this->repository->detalhar($id);
    }

    public function cadastrarEditar(Request $request, ?int $id)
    {
        $body = $request->all();

        $validator = Validator::make($body, [
            'descricao' => 'required',
            'valor_venda' => 'required',
            'estoque' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors());
        }

        $produtoBody = [
            'descricao' => $body['descricao'],
            'valor_venda' => $body['valor_venda'],
            'estoque' => $body['estoque']
        ];

        $produto = $this->repository->cadastrarEditar(['id' => $id], $produtoBody);

        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $imagem) {
                $upload = $imagem->store('public/imagens');

                $upload = str_replace("public/", "storage/", $upload);

                $this->repository->adicionarImagem($produto, $upload);
            }
        }

        if ($produto) {
            $this->repository->cadastrarHistorico($produto, ['tipo' => 'Entrada', 'quantidade' => $body['estoque']]);
        }

        $mesagem = isset($id) ? 'Informações alteradas com sucesso!' : 'Porduto cadastrado com sucesso!';

        return $mesagem;
    }

    public function excluir(int $id)
    {
        $produto = $this->repository->excluir($id);

        if ($produto) {
            foreach ($produto->imagens as $imagem) {
                if (Storage::exists($imagem->url)) Storage::delete($imagem->url);
            }
        }

        return $produto;
    }

    public function excluirImagem(int $id)
    {
        $imagem = $this->repository->excluirImagem($id);

        if ($imagem) {
            if (Storage::exists($imagem->url)) Storage::delete($imagem->url);
        }

        return $imagem;
    }
}
