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
                        'price' => $product->price,
                        'quantidade' => $item['quantity'],
                        'peso' => $product->peso ?? 0.3,
                        'comprimento' => $product->comprimento ?? 20,
                        'largura' => $product->largura ?? 15,
                        'altura' => $product->altura ?? 5
                    ];
                }
            }

            if (empty($itens)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Nenhum produto válido encontrado'
                ], 400);
            }

            // Calcular frete usando o CorreiosService
            $resultado = $this->correiosService->calcularFrete($request->cep_destino, $itens);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao calcular frete: ' . $e->getMessage()
            ], 500);
        }
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
        
        if (!$this->correiosService->validarCep($cep)) {
            return response()->json([
                'success' => false,
                'error' => 'CEP inválido'
            ], 400);
        }

        // Consultar CEP via ViaCEP
        $endereco = $this->correiosService->consultarCep($cep);
        
        return response()->json([
            'success' => true,
            'cep' => $this->correiosService->formatarCep($cep),
            'valido' => true,
            'endereco' => $endereco
        ]);
    }
}
