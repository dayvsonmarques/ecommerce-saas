<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\CorreiosService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    private $correiosService;

    public function __construct(CorreiosService $correiosService)
    {
        $this->correiosService = $correiosService;
    }

    /**
     * Calcula o frete para um carrinho
     */
    public function calcularFrete(Request $request): JsonResponse
    {
        $request->validate([
            'cep_destino' => 'required|string|size:9',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        try {
            // Buscar produtos do carrinho
            $productIds = collect($request->items)->pluck('id');
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

            // Preparar itens para cálculo
            $itens = [];
            foreach ($request->items as $item) {
                $product = $products->get($item['id']);
                if ($product) {
                    $itens[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $item['quantity'],
                        'peso' => $product->peso ?? 0.3,
                        'comprimento' => $product->comprimento ?? 20,
                        'largura' => $product->largura ?? 15,
                        'altura' => $product->altura ?? 5
                    ];
                }
            }

            // Calcular dimensões do carrinho
            $dimensoes = $this->correiosService->calcularDimensoesCarrinho($itens);

            // Preparar dados para os Correios
            $dadosCorreios = [
                'cep_origem' => config('services.correios.cep_origem'),
                'cep_destino' => $request->cep_destino,
                'peso' => $dimensoes['peso'],
                'comprimento' => $dimensoes['comprimento'],
                'largura' => $dimensoes['largura'],
                'altura' => $dimensoes['altura'],
                'valor_declarado' => $this->calcularValorTotal($itens)
            ];

            // Calcular frete para cada serviço
            $servicos = $this->correiosService->getServicosDisponiveis();
            $opcoesFrete = [];

            foreach ($servicos as $codigo => $servico) {
                $dadosCorreios['servico'] = $codigo;
                $resultado = $this->correiosService->calcularFrete($dadosCorreios);

                if (!empty($resultado) && !$resultado[0]['erro']) {
                    $opcoesFrete[] = [
                        'codigo' => $codigo,
                        'nome' => $servico['nome'],
                        'descricao' => $servico['descricao'],
                        'prazo_estimado' => $servico['prazo_estimado'],
                        'valor' => $resultado[0]['valor'],
                        'prazo' => $resultado[0]['prazo'],
                        'mensagem' => $resultado[0]['mensagem']
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'opcoes' => $opcoesFrete,
                'cep_destino' => $request->cep_destino
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao calcular frete: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula o valor total dos itens
     */
    private function calcularValorTotal(array $itens): float
    {
        $total = 0;
        foreach ($itens as $item) {
            $product = \App\Models\Product::find($item['id']);
            if ($product) {
                $total += $product->price * $item['quantity'];
            }
        }
        return $total;
    }

    /**
     * Valida CEP
     */
    public function validarCep(Request $request): JsonResponse
    {
        $request->validate([
            'cep' => 'required|string|size:9'
        ]);

        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        
        if (strlen($cep) !== 8) {
            return response()->json([
                'success' => false,
                'error' => 'CEP inválido'
            ], 400);
        }

        // Aqui você pode integrar com uma API de CEP como ViaCEP
        // Por enquanto, vamos apenas validar o formato
        return response()->json([
            'success' => true,
            'cep' => $request->cep,
            'valido' => true
        ]);
    }
}
