<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CorreiosService
{
    private $baseUrl = 'https://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
    private $codigoEmpresa;
    private $senha;
    private $cepOrigem;

    public function __construct()
    {
        $this->codigoEmpresa = config('services.correios.codigo_empresa');
        $this->senha = config('services.correios.senha');
        $this->cepOrigem = config('services.correios.cep_origem', '01310-100');
    }

    /**
     * Calcula o frete para um carrinho de produtos
     */
    public function calcularFrete(string $cepDestino, array $products): array
    {
        try {
            // Calcular dimensões totais do carrinho
            $dimensions = $this->calcularDimensoesCarrinho($products);
            
            // Preparar dados para os Correios
            $dadosCorreios = [
                'cep_origem' => $this->cepOrigem,
                'cep_destino' => $cepDestino,
                'peso' => $dimensions['peso'],
                'comprimento' => $dimensions['comprimento'],
                'largura' => $dimensions['largura'],
                'altura' => $dimensions['altura'],
                'valor_declarado' => $this->calcularValorTotal($products)
            ];

            // Calcular frete para cada serviço
            $servicos = $this->getServicosDisponiveis();
            $opcoesFrete = [];

            foreach ($servicos as $codigo => $servico) {
                $dadosCorreios['servico'] = $codigo;
                $resultado = $this->chamarAPICorreios($dadosCorreios);

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

            return [
                'success' => true,
                'opcoes' => $opcoesFrete,
                'cep_destino' => $cepDestino,
                'dimensions' => $dimensions
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao calcular frete: ' . $e->getMessage());
            return $this->getFreteFallback($products, $cepDestino);
        }
    }

    /**
     * Calcula as dimensões totais do carrinho
     */
    public function calcularDimensoesCarrinho(array $products): array
    {
        $pesoTotal = 0;
        $volumeTotal = 0;
        $maiorDimensao = 0;

        foreach ($products as $product) {
            $peso = $product['peso'] ?? 0.3;
            $comprimento = $product['comprimento'] ?? 20;
            $largura = $product['largura'] ?? 15;
            $altura = $product['altura'] ?? 5;
            $quantidade = $product['quantidade'] ?? 1;

            $pesoTotal += $peso * $quantidade;
            $volumeTotal += ($comprimento * $largura * $altura) * $quantidade;
            
            $maiorDimensao = max($maiorDimensao, $comprimento, $largura, $altura);
        }

        // Cálculo das dimensões da caixa
        $dimensoes = $this->calcularDimensoesCaixa($volumeTotal, $maiorDimensao);

        return [
            'peso' => max($pesoTotal, 0.3), // Mínimo 300g
            'comprimento' => $dimensoes['comprimento'],
            'largura' => $dimensoes['largura'],
            'altura' => $dimensoes['altura']
        ];
    }

    /**
     * Calcula as dimensões da caixa baseado no volume
     */
    private function calcularDimensoesCaixa(float $volume, float $maiorDimensao): array
    {
        // Dimensões padrão para diferentes volumes
        if ($volume <= 1000) { // Até 1L
            return ['comprimento' => 20, 'largura' => 15, 'altura' => 5];
        } elseif ($volume <= 5000) { // Até 5L
            return ['comprimento' => 30, 'largura' => 20, 'altura' => 10];
        } elseif ($volume <= 15000) { // Até 15L
            return ['comprimento' => 40, 'largura' => 30, 'altura' => 15];
        } else {
            // Para volumes maiores, usar a maior dimensão como base
            $base = max($maiorDimensao, 20);
            return [
                'comprimento' => $base,
                'largura' => $base * 0.8,
                'altura' => $base * 0.5
            ];
        }
    }

    /**
     * Calcula o valor total dos itens
     */
    private function calcularValorTotal(array $products): float
    {
        $total = 0;
        foreach ($products as $product) {
            $total += $product['price'] * ($product['quantidade'] ?? 1);
        }
        return $total;
    }

    /**
     * Chama a API dos Correios
     */
    private function chamarAPICorreios(array $dados): array
    {
        $params = [
            'nCdEmpresa' => $this->codigoEmpresa,
            'sDsSenha' => $this->senha,
            'nCdServico' => $dados['servico'],
            'sCepOrigem' => $dados['cep_origem'],
            'sCepDestino' => $dados['cep_destino'],
            'nVlPeso' => $dados['peso'],
            'nCdFormato' => 1, // 1 = Caixa/pacote
            'nVlComprimento' => $dados['comprimento'],
            'nVlAltura' => $dados['altura'],
            'nVlLargura' => $dados['largura'],
            'nVlDiametro' => 0,
            'sCdMaoPropria' => 'N',
            'nVlValorDeclarado' => $dados['valor_declarado'],
            'sCdAvisoRecebimento' => 'N',
            'StrRetorno' => 'xml'
        ];

        $response = Http::timeout(30)->get($this->baseUrl, $params);
        
        if ($response->successful()) {
            return $this->processarRespostaCorreios($response->body());
        }
        
        throw new \Exception('Erro na comunicação com os Correios');
    }

    /**
     * Processa a resposta XML dos Correios
     */
    private function processarRespostaCorreios(string $xml): array
    {
        $xml = simplexml_load_string($xml);
        
        if (!$xml) {
            throw new \Exception('Resposta inválida dos Correios');
        }

        $resultados = [];
        
        foreach ($xml->cServico as $servico) {
            $erro = (string) $servico->Erro;
            
            if ($erro == '0') {
                $resultados[] = [
                    'codigo' => (string) $servico->Codigo,
                    'valor' => (float) str_replace(',', '.', (string) $servico->Valor),
                    'prazo' => (int) $servico->PrazoEntrega,
                    'erro' => false,
                    'mensagem' => 'OK'
                ];
            } else {
                $resultados[] = [
                    'codigo' => (string) $servico->Codigo,
                    'valor' => 0,
                    'prazo' => 0,
                    'erro' => true,
                    'mensagem' => (string) $servico->MsgErro
                ];
            }
        }
        
        return $resultados;
    }

    /**
     * Retorna frete fallback em caso de erro
     */
    private function getFreteFallback(array $products, string $cepDestino): array
    {
        $totalValue = $this->calcularValorTotal($products);
        
        return [
            'success' => true,
            'opcoes' => [
                [
                    'codigo' => '04014',
                    'nome' => 'SEDEX',
                    'descricao' => 'Entrega expressa',
                    'prazo_estimado' => '1-3 dias úteis',
                    'valor' => min(15.00 + ($totalValue * 0.02), 50.00),
                    'prazo' => 3,
                    'mensagem' => 'Frete estimado (serviço temporariamente indisponível)'
                ],
                [
                    'codigo' => '04510',
                    'nome' => 'PAC',
                    'descricao' => 'Encomenda econômica',
                    'prazo_estimado' => '3-7 dias úteis',
                    'valor' => min(10.00 + ($totalValue * 0.015), 35.00),
                    'prazo' => 5,
                    'mensagem' => 'Frete estimado (serviço temporariamente indisponível)'
                ]
            ],
            'cep_destino' => $cepDestino,
            'dimensions' => $this->calcularDimensoesCarrinho($products)
        ];
    }

    /**
     * Obtém os serviços disponíveis
     */
    public function getServicosDisponiveis(): array
    {
        return [
            '04014' => [
                'nome' => 'SEDEX',
                'descricao' => 'Entrega expressa',
                'prazo_estimado' => '1-3 dias úteis'
            ],
            '04510' => [
                'nome' => 'PAC',
                'descricao' => 'Encomenda econômica',
                'prazo_estimado' => '3-7 dias úteis'
            ]
        ];
    }

    /**
     * Valida CEP
     */
    public function validarCep(string $cep): bool
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        return strlen($cep) === 8;
    }

    /**
     * Formata CEP
     */
    public function formatarCep(string $cep): string
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
        }
        return $cep;
    }

    /**
     * Consulta CEP via ViaCEP
     */
    public function consultarCep(string $cep): ?array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get("https://viacep.com.br/ws/{$cep}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['erro'])) {
                    return null;
                }
                
                return [
                    'cep' => $data['cep'],
                    'logradouro' => $data['logradouro'],
                    'complemento' => $data['complemento'],
                    'bairro' => $data['bairro'],
                    'localidade' => $data['localidade'],
                    'uf' => $data['uf'],
                    'ibge' => $data['ibge'],
                    'gia' => $data['gia'],
                    'ddd' => $data['ddd'],
                    'siafi' => $data['siafi']
                ];
            }
        } catch (\Exception $e) {
            Log::error('Erro ao consultar CEP: ' . $e->getMessage());
        }
        
        return null;
    }
}