<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CorreiosService
{
    private $baseUrl = 'https://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
    private $codigoEmpresa;
    private $senha;

    public function __construct()
    {
        $this->codigoEmpresa = config('services.correios.codigo_empresa');
        $this->senha = config('services.correios.senha');
    }

    /**
     * Calcula o frete para um pedido
     */
    public function calcularFrete(array $dados)
    {
        try {
            $params = $this->prepararParametros($dados);
            
            $response = Http::timeout(30)->get($this->baseUrl, $params);
            
            if ($response->successful()) {
                return $this->processarResposta($response->body());
            }
            
            throw new \Exception('Erro na comunicação com os Correios');
            
        } catch (\Exception $e) {
            Log::error('Erro ao calcular frete dos Correios: ' . $e->getMessage());
            return $this->getFreteFallback($dados);
        }
    }

    /**
     * Prepara os parâmetros para a requisição
     */
    private function prepararParametros(array $dados)
    {
        return [
            'nCdEmpresa' => $this->codigoEmpresa,
            'sDsSenha' => $this->senha,
            'nCdServico' => $dados['servico'], // 04014 = SEDEX, 04510 = PAC
            'sCepOrigem' => $dados['cep_origem'],
            'sCepDestino' => $dados['cep_destino'],
            'nVlPeso' => $dados['peso'],
            'nCdFormato' => 1, // 1 = Caixa/pacote
            'nVlComprimento' => $dados['comprimento'],
            'nVlAltura' => $dados['altura'],
            'nVlLargura' => $dados['largura'],
            'nVlDiametro' => 0,
            'sCdMaoPropria' => 'N',
            'nVlValorDeclarado' => $dados['valor_declarado'] ?? 0,
            'sCdAvisoRecebimento' => 'N',
            'StrRetorno' => 'xml'
        ];
    }

    /**
     * Processa a resposta XML dos Correios
     */
    private function processarResposta(string $xml)
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
    private function getFreteFallback(array $dados)
    {
        return [
            [
                'codigo' => '04014',
                'valor' => 15.00,
                'prazo' => 3,
                'erro' => false,
                'mensagem' => 'Frete estimado (serviço temporariamente indisponível)'
            ],
            [
                'codigo' => '04510',
                'valor' => 10.00,
                'prazo' => 5,
                'erro' => false,
                'mensagem' => 'Frete estimado (serviço temporariamente indisponível)'
            ]
        ];
    }

    /**
     * Calcula dimensões e peso do carrinho
     */
    public function calcularDimensoesCarrinho(array $itens)
    {
        $pesoTotal = 0;
        $volumeTotal = 0;
        $maiorDimensao = 0;

        foreach ($itens as $item) {
            $peso = $item['peso'] ?? 0.3; // Peso padrão em kg
            $comprimento = $item['comprimento'] ?? 20; // cm
            $largura = $item['largura'] ?? 15; // cm
            $altura = $item['altura'] ?? 5; // cm

            $pesoTotal += $peso * $item['quantity'];
            $volumeTotal += ($comprimento * $largura * $altura) * $item['quantity'];
            
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
    private function calcularDimensoesCaixa(float $volume, float $maiorDimensao)
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
     * Obtém os serviços disponíveis
     */
    public function getServicosDisponiveis()
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
}
