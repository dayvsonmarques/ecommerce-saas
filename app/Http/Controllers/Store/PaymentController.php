<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private $mercadopagoService;

    public function __construct(MercadoPagoService $mercadopagoService)
    {
        $this->mercadopagoService = $mercadopagoService;
    }

    /**
     * Cria uma preferência de pagamento
     */
    public function criarPreferencia(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'items' => 'required|array|min:1',
            'payer' => 'required|array',
            'payer.name' => 'required|string|max:255',
            'payer.email' => 'required|email|max:255',
            'payer.phone' => 'nullable|string|max:20'
        ]);

        try {
            $order = \App\Models\Order::findOrFail($request->order_id);
            
            // Verificar se o pedido pertence ao usuário logado
            if (Auth::check() && $order->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Pedido não encontrado'
                ], 404);
            }

            // Preparar dados para o Mercado Pago
            $dados = [
                'order_id' => $order->id,
                'items' => $this->prepararItens($order),
                'payer' => $request->payer
            ];

            // Validar dados
            $validacao = $this->mercadopagoService->validarDadosPagamento($dados);
            if (!$validacao['valid']) {
                return response()->json([
                    'success' => false,
                    'errors' => $validacao['errors']
                ], 400);
            }

            // Criar preferência
            $resultado = $this->mercadopagoService->criarPreferencia($dados);

            if ($resultado['success']) {
                // Atualizar pedido com ID da preferência
                $order->update([
                    'payment_method' => 'mercadopago',
                    'notes' => 'Preferência MP: ' . $resultado['preference_id']
                ]);

                return response()->json([
                    'success' => true,
                    'preference_id' => $resultado['preference_id'],
                    'init_point' => config('services.mercadopago.sandbox') 
                        ? $resultado['sandbox_init_point'] 
                        : $resultado['init_point']
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $resultado['error']
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao criar preferência de pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processa webhook do Mercado Pago
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            $resultado = $this->mercadopagoService->processarWebhook($data);

            if ($resultado['success']) {
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'error' => $resultado['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao processar webhook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifica status de um pagamento
     */
    public function verificarStatus(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => 'required|string'
        ]);

        try {
            $resultado = $this->mercadopagoService->verificarStatusPagamento($request->payment_id);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao verificar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém métodos de pagamento disponíveis
     */
    public function getMetodosPagamento(): JsonResponse
    {
        try {
            $metodos = $this->mercadopagoService->getMetodosPagamento();

            return response()->json([
                'success' => true,
                'metodos' => $metodos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao obter métodos de pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepara itens do pedido para o Mercado Pago
     */
    private function prepararItens($order): array
    {
        $itens = [];

        foreach ($order->items as $item) {
            $itens[] = [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price
            ];
        }

        return $itens;
    }
}
