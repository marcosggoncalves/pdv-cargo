<?php

namespace App\Http\Controllers;

use App\Services\UsuarioService;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    protected $service;

    public function __construct(UsuarioService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/usuarios",
     *      operationId="listar_usuarios",
     *      tags={"usuario"},
     *      summary="listar_usuarios",
     *      description="Retornar todos os cadastros",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *       @OA\Schema(
     *           type="number"
     *       )
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Tudo certo!",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado, login é necessário . ",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno!",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *   )
     */
    public function usuarios()
    {
        try {
            $usuarios = $this->service->usuarios();

            return response()->json([
                'status' => true,
                'usuarios' => $usuarios
            ]);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar usuarios!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/usuario",
     *   tags={"usuario"},
     *   summary="Cadastrar Novo Usuario",
     *   operationId="new_usuario",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","email", "password", "nivel"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   ),
     *    @OA\Response(
     *      response=422,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function cadastrar(Request $request)
    {
        try {
            $usuario = $this->service->cadastrar($request);

            return Response([
                'status' => true,
                'message' => 'Usuário cadastrado com sucesso!',
                $usuario
            ]);
        } catch (\InvalidArgumentException $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel realizar cadastro, Campos são obrigatórios',
                'errors' => json_decode($e->getMessage(), true)
            ], 422);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel realizar cadastro!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Put(
     ** path="/api/v1/usuario/{id}",
     *   tags={"usuario"},
     *   summary="Alterar cadastro do usuario",
     *   operationId="editar_usuario",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","email", "password", "nivel"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   ),
     *    @OA\Response(
     *      response=422,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function editar(Request $request, $id)
    {
        try {
            $this->service->editar($id, $request);

            return Response([
                'status' => true,
                'message' => 'Cadastro alterado com sucesso!'
            ]);
        } catch (\InvalidArgumentException $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel realizar cadastro, Campos são obrigatórios',
                'errors' => json_decode($e->getMessage(), true)
            ], 422);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel alterar cadastro!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     ** path="/api/v1/usuario/{id}",
     *   tags={"usuario"},
     *   summary="Excluir cadastro de um usuario",
     *   operationId="excluir_usuario",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   )
     *)
     **/
    public function excluir($id)
    {
        try {
            $this->service->excluir($id);

            return Response()->json([
                'status' => true,
                'message' => 'Usuário excluido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel excluir usuário!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/login",
     *   tags={"login"},
     *   summary="Efetuar login ",
     *   operationId="login_usuario",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   ),
     *    @OA\Response(
     *      response=422,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function login(Request $request)
    {
        try {
            return $this->service->login($request);
        } catch (\InvalidArgumentException $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel realizar cadastro!',
                'errors' => json_decode($e->getMessage(), true)
            ], 422);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Post(
     *      path="/api/v1/login-check",
     *      operationId="login_usuario_check",
     *      tags={"login"},
     *      summary="Verificar Token",
     *      description="Verificar Token",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Tudo certo!",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado, login é necessário . ",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno!",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *   )
     */
    public function check()
    {
        try {
            return $this->service->loginCheck();
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
