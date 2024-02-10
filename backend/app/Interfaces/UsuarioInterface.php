<?php

namespace App\Interfaces;

interface UsuarioInterface
{
    public function usuarios();

    public function cadastrar(array $body);

    public function editar(int $id, array $body);

    public function excluir(int $id);
}
