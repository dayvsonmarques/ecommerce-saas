<?php

namespace App\Services;

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Payment;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    private $accessToken;
    private $publicKey;

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token');
        $this->publicKey = config('services.mercadopago.public_key');
        
        SDK::setAccessToken($this->accessToken);
        SDK::setIntegratorId(config('services.mercadopago.integrator_id'));
    }

    /**
     * Cria uma preferência de pagamento
     */
    public function criarPreferencia(array $dados)
    {
        try {
            $preference = new Preference();

            // Configurar itens
            $items = [];
            foreach ($dados['items'] as $item) {
                $mpItem = new Item();
                $mpItem->title = $item['name'];
                $mpItem->quantity = $item['quantity'];
                $mpItem->unit_price = $item['price'];
                $mpItem->currency_id = 'BRL';
                $items[] = $mpItem;
            }

            $preference->items = $items;

            // Configurar pagador
            $payer = new Payer();
            $payer->name = $dados['payer']['name'];
            $payer->email = $dados['payer']['email'];
            $payer->phone = [
                'number' => $dados['payer']['phone'] ?? ''
            ];
            
            $preference->payer = $payer;

            // Configurar URLs de retorno
            $preference->back_urls = [
                'success' => route('store.checkout.success'),
                'failure' => route('store.checkout.failure'),
                'pending' => route('store.checkout.pending')
            ];

            $preference->auto_return = 'approved';

            // Configurar informações adicionais
            $preference->external_reference = $dados['order_id'];
            $preference->notification_url = route('mercadopago.webhook');

            // Configurar métodos de pagamento
            $preference->payment_methods = [
                'excluded_payment_methods' => [],
                'excluded_payment_types' => [],
                'installments' => 12
            ];

            $preference->save();

            return [
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar preferência Mercado Pago: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Processa um pagamento
     */
    public function processarPagamento(string $paymentId)
    {
        try {
            $payment = Payment::find_by_id($paymentId);

            return [
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'status' => $payment->status,
                    'status_detail' => $payment->status_detail,
                    'transaction_amount' => $payment->transaction_amount,
                    'currency_id' => $payment->currency_id,
                    'external_reference' => $payment->external_reference,
                    'payment_method_id' => $payment->payment_method_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'date_approved' => $payment->date_approved,
                    'date_created' => $payment->date_created
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento Mercado Pago: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica o status de um pagamento
     */
    public function verificarStatusPagamento(string $paymentId)
    {
        try {
            $payment = Payment::find_by_id($paymentId);

            return [
                'success' => true,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'approved' => $payment->status === 'approved'
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do pagamento: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Processa webhook do Mercado Pago
     */
    public function processarWebhook(array $data)
    {
        try {
            if (!isset($data['type']) || $data['type'] !== 'payment') {
                return ['success' => false, 'error' => 'Tipo de webhook inválido'];
            }

            $paymentId = $data['data']['id'] ?? null;
            if (!$paymentId) {
                return ['success' => false, 'error' => 'ID do pagamento não encontrado'];
            }

            $resultado = $this->processarPagamento($paymentId);
            
            if ($resultado['success']) {
                $payment = $resultado['payment'];
                
                // Atualizar status do pedido baseado no status do pagamento
                $this->atualizarStatusPedido($payment);
            }

            return $resultado;

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook Mercado Pago: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza o status do pedido baseado no pagamento
     */
    private function atualizarStatusPedido(array $payment)
    {
        try {
            $orderId = $payment['external_reference'];
            $status = $payment['status'];

            $order = \App\Models\Order::find($orderId);
            if (!$order) {
                Log::warning("Pedido não encontrado: {$orderId}");
                return;
            }

            switch ($status) {
                case 'approved':
                    $order->update(['status' => 'processing']);
                    break;
                case 'pending':
                    $order->update(['status' => 'pending']);
                    break;
                case 'rejected':
                case 'cancelled':
                    $order->update(['status' => 'cancelled']);
                    break;
                case 'refunded':
                    $order->update(['status' => 'cancelled']);
                    break;
            }

            Log::info("Status do pedido {$orderId} atualizado para: {$status}");

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status do pedido: ' . $e->getMessage());
        }
    }

    /**
     * Obtém métodos de pagamento disponíveis
     */
    public function getMetodosPagamento()
    {
        return [
            'credit_card' => [
                'nome' => 'Cartão de Crédito',
                'icone' => 'credit-card',
                'descricao' => 'Visa, Mastercard, Elo, American Express'
            ],
            'debit_card' => [
                'nome' => 'Cartão de Débito',
                'icone' => 'credit-card',
                'descricao' => 'Visa, Mastercard, Elo'
            ],
            'pix' => [
                'nome' => 'PIX',
                'icone' => 'pix',
                'descricao' => 'Pagamento instantâneo'
            ],
            'boleto' => [
                'nome' => 'Boleto Bancário',
                'icone' => 'barcode',
                'descricao' => 'Pagamento em até 3 dias úteis'
            ]
        ];
    }

    /**
     * Valida dados do pagamento
     */
    public function validarDadosPagamento(array $dados)
    {
        $errors = [];

        if (empty($dados['items'])) {
            $errors[] = 'Itens do pedido são obrigatórios';
        }

        if (empty($dados['payer']['name'])) {
            $errors[] = 'Nome do pagador é obrigatório';
        }

        if (empty($dados['payer']['email'])) {
            $errors[] = 'Email do pagador é obrigatório';
        }

        if (!filter_var($dados['payer']['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email do pagador é inválido';
        }

        if (empty($dados['order_id'])) {
            $errors[] = 'ID do pedido é obrigatório';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
