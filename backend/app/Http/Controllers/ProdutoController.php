<?php

namespace App\Http\Controllers;

use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    protected $service;

    public function __construct(ProdutoService $service)
    {
        $this->service = $service;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/produtos",
     *      operationId="listar_produtos",
     *      tags={"produto"},
     *      summary="Listar produtos",
     *      description="Retornar todos os produtos",
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
    public function produtos()
    {
        try {
            $produtos = $this->service->produtos();

            return response()->json([
                'status' => true,
                'produtos' => $produtos
            ]);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar produtos!',
                'error' => $e->__toString()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/produtos-listagem",
     *      operationId="listar_produtos_sem_paginacao",
     *      tags={"produto"},
     *      summary="Listar produtos sem paginação",
     *      description="Retornar todos os produtos",
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
    public function listagem()
    {
        try {
            $produtos = $this->service->listagem();

            return response()->json($produtos);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar produtos!',
                'error' => $e->__toString()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/produto/{id}",
     *      operationId="detalhar_produto",
     *      tags={"produto"},
     *      summary="Detalhar produtos",
     *      description="Retornar todos os produtos",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *              type="number"
     *         )
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
    public function detalhar($id)
    {
        try {
            $produto = $this->service->detalhar($id);

            return response()->json([
                'status' => true,
                'produto' => $produto
            ]);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel detalhar produto!',
                'error' => $e->__toString()
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/produto{id?}",
     *   tags={"produto"},
     *   summary="Cadastrar/Alterar produto",
     *   operationId="new_produto",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=false,
     *         @OA\Schema(
     *              type="number"
     *         )
     *      ),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastro",
     *    @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="descricao", type="name", format="text", example="Amortecedor Dianteiro"),
     *                 @OA\Property(property="valor_venda", type="number", format="number", example=1),
     *                 @OA\Property(property="estoque", type="number", format="number", example=1),
     *                 @OA\Property(property="imagens[]", type="file", format="binary", example=1),
     *             )
     *         ),
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
    public function cadastrarEditar(Request $request, int $id = null)
    {
        try {
            $produto = $this->service->cadastrarEditar($request, $id);

            return Response([
                'status' => true,
                'message' =>   $produto,
                'produto' => $produto
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
     * @OA\Delete(
     ** path="/api/v1/produto/{id}",
     *   tags={"produto"},
     *   summary="Excluir cadastro do produto",
     *   operationId="delete_produto",
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
            $delete = $this->service->excluir($id);

            if (!$delete) {
                return Response()->json([
                    'status' => false,
                    'message' => 'Não foi possivel excluir o cadastro do produto!'
                ], 422);
            }

            return Response()->json([
                'status' => true,
                'message' => 'Produto foi excluido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel excluir produto!',
                'error' => $e->__toString()
            ], 500);
        }
    }
    /**
     * @OA\Delete(
     ** path="/api/v1/produto-excluir-imagem/{id}",
     *   tags={"produto"},
     *   summary="Excluir imagem do produto",
     *   operationId="excluir_imagem_produto",
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
    public function excluirImagem($id)
    {
        try {
            $this->service->excluirImagem($id);

            return Response()->json([
                'status' => true,
                'message' => 'Imagem excluida!'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel remover imagem!',
                'error' => $e->__toString()
            ], 500);
        }
    }
}
