<?php

namespace App\Http\Controllers;

use App\Services\PedidoService;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    protected $service;

    public function __construct(PedidoService $service)
    {
        $this->service = $service;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/pedidos",
     *      operationId="listar_pedidos",
     *      tags={"pedido"},
     *      summary="Listar pedidos",
     *      description="Retornar todos os pedidos",
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
    public function pedidos()
    {
        try {
            $pedidos = $this->service->pedidos();

            return response()->json([
                'status' => true,
                'pedidos' => $pedidos
            ]);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar pedidos!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/pedido/{id}",
     *      operationId="detalhar_pedido",
     *      tags={"pedido"},
     *      summary="Detalhar pedido",
     *      description="Retornar todos os pedidos",
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
            $pedido = $this->service->detalhar($id);

            return response()->json([
                'status' => true,
                'pedido' => $pedido
            ]);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel detalhar pedido!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/relizar-pedido",
     *   tags={"pedido"},
     *   summary="Realizar novo pedido",
     *   operationId="realizar_pedido",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para realizar o pedido",
     *    @OA\JsonContent(
     *               required={"produtos"},
     *               @OA\Property(
     *                 property="produtos",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="produto_id", type="integer"),
     *                     @OA\Property(property="quantidade", type="integer"),
     *                 )
     *             ),
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
    /**
     * @OA\Post(
     ** path="/api/v1/relizar-pedido",
     *   tags={"pedido"},
     *   summary="Realizar novo pedido",
     *   operationId="realizar_pedido",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para realizar o pedido",
     *    @OA\JsonContent(
     *               required={"produtos"},
     *               @OA\Property(
     *                 property="produtos",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="produto_id", type="integer"),
     *                     @OA\Property(property="quantidade", type="integer"),
     *                 )
     *             ),
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
    public function realizarPedidoAdicionarProduto(Request $request, ?string $pedidoID = null)
    {
        try {
            $this->service->realizarPedidoAdicionarProduto($request, $pedidoID);

            return Response([
                'status' => true,
                'message' => 'Pedido concluido com com sucesso!'
            ]);
        } catch (\Exception $e) {
            return Response([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Delete(
     ** path="/api/v1/pedido/{id}",
     *   tags={"pedido"},
     *   summary="Excluir pedido",
     *   operationId="excluir_pedido",
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
                'message' => 'Pedido excluido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel excluir pedido!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Delete(
     ** path="/api/v1/pedido-item/{id}",
     *   tags={"pedido"},
     *   summary="Excluir item do pedido",
     *   operationId="excluir_item_pedido",
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
    public function excluirItemPedido($id)
    {
        try {
            $this->service->excluirItemPedido($id);

            return Response()->json([
                'status' => true,
                'message' => 'Item excluido do pedido!'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel remover item do pedido!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
