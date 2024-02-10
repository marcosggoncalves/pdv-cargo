<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Interfaces\UsuarioInterface;

class UsuarioRepository implements UsuarioInterface
{
    public function usuarios()
    {
        return Usuario::orderBy('nome')->paginate(15);
    }

    public function cadastrar(array $body)
    {
        return Usuario::create($body);
    }

    public function editar(int $id, array $body)
    {
        $user = Usuario::findOrFail($id);

        return $user->update($body);

    }

    public function excluir(int $id)
    {
        return Usuario::destroy($id);
    }
}
