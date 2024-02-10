<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UsuarioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsuarioService
{
    protected $repository;

    public function __construct(UsuarioRepository $repository)
    {
        $this->repository = $repository;
    }

    public function hashPassword($password)
    {
        return Hash::make($password);
    }

    public function usuarios()
    {
        return $this->repository->usuarios();
    }

    public function cadastrar(Request $request)
    {
        $body = $request->all();

        $validator = Validator::make($body, [
            'nome' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string|email|unique:usuario,email'
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors());
        }

        $senhaCriptografata = $this->HashPassword($body['password']);

        $usuario = ['nome' => $body['nome'], 'email' => $body['email'], 'password' => $senhaCriptografata];

        return $this->repository->cadastrar($usuario);
    }

    public function editar(int $id, Request $request)
    {
        $body = $request->all();

        $validator = Validator::make($body, [
            'nome' => 'required|string',
            'email' => 'required|string|email'
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors());
        }

        $usuario = ['nome' => $body['nome'], 'email' => $body['email']];

        if (isset($body['password']) && !empty($body['password'])) {
            $usuario['password'] = $this->HashPassword($body['password']);
        }

        return $this->repository->editar($id, $usuario);
    }

    public function excluir($id)
    {
        return  $this->repository->excluir($id);
    }

    public function loginCheck(){
         if (Auth::check()) {
            return response()->json([
                'status' => true,
                'message' => "Token válido"
            ]);
        }

        throw new \Exception('Não autorizado!');
    }

    public function login($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors());
        }

        $token = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$token) {
            throw new \Exception('Email/ou Senha inválido(s)!');
        }

        $usuario =  Auth::user();

        return [
            'status' => true,
            'token' => $token,
            'usuario' => $usuario,
        ];
    }
}
