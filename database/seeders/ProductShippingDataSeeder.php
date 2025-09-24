<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductShippingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            $shippingData = $this->generateShippingData($product->name);
            
            $product->update($shippingData);
        }

        $this->command->info('Dados de frete dos produtos atualizados com sucesso!');
    }

    /**
     * Gera dados realistas de frete baseados no nome do produto
     */
    private function generateShippingData(string $productName): array
    {
        $name = strtolower($productName);
        $data = [];

        // Gerar SKU único
        $data['sku'] = 'SKU-' . strtoupper(substr(md5($productName), 0, 8));

        // Gerar marca baseada no tipo de produto
        $data['marca'] = $this->generateBrand($name);

        // Gerar material baseado no tipo de produto
        $data['material'] = $this->generateMaterial($name);

        // Gerar tamanhos baseados no tipo de produto
        $data['tamanhos'] = $this->generateSizes($name);

        // Gerar cores disponíveis
        $data['cores'] = $this->generateColors($name);

        // Gerar dados de dimensões e peso
        $dimensions = $this->generateDimensions($name);
        $data = array_merge($data, $dimensions);

        // Gerar dados de estoque
        $data['estoque_minimo'] = rand(5, 20);
        $data['controlar_estoque'] = true;

        // Gerar dados de SEO
        $data['meta_titulo'] = $productName . ' - Loja Online';
        $data['meta_descricao'] = 'Compre ' . $productName . ' com qualidade e entrega rápida. Confira nossos preços e promoções!';
        $data['tags'] = $this->generateTags($name);

        return $data;
    }

    /**
     * Gera marca baseada no tipo de produto
     */
    private function generateBrand(string $name): string
    {
        $brands = [
            'Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour',
            'Zara', 'H&M', 'Uniqlo', 'Gap', 'Levi\'s',
            'Calvin Klein', 'Tommy Hilfiger', 'Ralph Lauren', 'Lacoste', 'Polo',
            'Vans', 'Converse', 'New Balance', 'Asics', 'Fila'
        ];

        // Lógica para escolher marca baseada no tipo
        if (str_contains($name, 'tênis') || str_contains($name, 'sapato')) {
            return $brands[array_rand(array_slice($brands, 0, 5))]; // Marcas esportivas
        } elseif (str_contains($name, 'camiseta') || str_contains($name, 'camisa')) {
            return $brands[array_rand(array_slice($brands, 5, 5))]; // Marcas de moda
        } else {
            return $brands[array_rand($brands)];
        }
    }

    /**
     * Gera material baseado no tipo de produto
     */
    private function generateMaterial(string $name): string
    {
        $materials = [
            'Algodão', 'Poliester', 'Viscose', 'Elastano', 'Linho',
            'Seda', 'Lã', 'Couro', 'Camurça', 'Jeans',
            'Malha', 'Tricot', 'Brim', 'Sarja', 'Cetim'
        ];

        if (str_contains($name, 'camiseta') || str_contains($name, 'camisa')) {
            return 'Algodão 100%';
        } elseif (str_contains($name, 'calça') || str_contains($name, 'jeans')) {
            return 'Jeans';
        } elseif (str_contains($name, 'vestido') || str_contains($name, 'saia')) {
            return $materials[array_rand(array_slice($materials, 0, 8))];
        } elseif (str_contains($name, 'jaqueta') || str_contains($name, 'casaco')) {
            return 'Poliester';
        } else {
            return $materials[array_rand($materials)];
        }
    }

    /**
     * Gera tamanhos baseados no tipo de produto
     */
    private function generateSizes(string $name): array
    {
        if (str_contains($name, 'camiseta') || str_contains($name, 'camisa') || str_contains($name, 'blusa')) {
            return ['P', 'M', 'G', 'GG', 'XG'];
        } elseif (str_contains($name, 'calça') || str_contains($name, 'jeans')) {
            return ['36', '38', '40', '42', '44', '46', '48'];
        } elseif (str_contains($name, 'vestido') || str_contains($name, 'saia')) {
            return ['P', 'M', 'G', 'GG'];
        } elseif (str_contains($name, 'sapato') || str_contains($name, 'tênis')) {
            return ['35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];
        } elseif (str_contains($name, 'meia')) {
            return ['P', 'M', 'G'];
        } else {
            return ['Único'];
        }
    }

    /**
     * Gera cores disponíveis
     */
    private function generateColors(string $name): array
    {
        $allColors = [
            'Preto', 'Branco', 'Azul', 'Vermelho', 'Verde',
            'Amarelo', 'Rosa', 'Roxo', 'Laranja', 'Marrom',
            'Cinza', 'Bege', 'Navy', 'Bordô', 'Coral'
        ];

        // Retorna 3-5 cores aleatórias
        $numColors = rand(3, 5);
        return array_slice($allColors, 0, $numColors);
    }

    /**
     * Gera dimensões realistas baseadas no tipo de produto
     */
    private function generateDimensions(string $name): array
    {
        if (str_contains($name, 'camiseta') || str_contains($name, 'camisa') || str_contains($name, 'blusa')) {
            return [
                'peso' => 0.2,
                'comprimento' => 30,
                'largura' => 25,
                'altura' => 2
            ];
        } elseif (str_contains($name, 'calça') || str_contains($name, 'jeans')) {
            return [
                'peso' => 0.6,
                'comprimento' => 35,
                'largura' => 30,
                'altura' => 3
            ];
        } elseif (str_contains($name, 'vestido') || str_contains($name, 'saia')) {
            return [
                'peso' => 0.4,
                'comprimento' => 40,
                'largura' => 30,
                'altura' => 2
            ];
        } elseif (str_contains($name, 'jaqueta') || str_contains($name, 'casaco') || str_contains($name, 'blazer')) {
            return [
                'peso' => 0.8,
                'comprimento' => 45,
                'largura' => 35,
                'altura' => 4
            ];
        } elseif (str_contains($name, 'short') || str_contains($name, 'bermuda')) {
            return [
                'peso' => 0.3,
                'comprimento' => 30,
                'largura' => 25,
                'altura' => 2
            ];
        } elseif (str_contains($name, 'meia')) {
            return [
                'peso' => 0.05,
                'comprimento' => 20,
                'largura' => 15,
                'altura' => 1
            ];
        } elseif (str_contains($name, 'sapato') || str_contains($name, 'tênis') || str_contains($name, 'bota')) {
            return [
                'peso' => 0.8,
                'comprimento' => 35,
                'largura' => 25,
                'altura' => 15
            ];
        } elseif (str_contains($name, 'bolsa') || str_contains($name, 'mochila') || str_contains($name, 'carteira')) {
            return [
                'peso' => 0.5,
                'comprimento' => 30,
                'largura' => 20,
                'altura' => 10
            ];
        } elseif (str_contains($name, 'cinto')) {
            return [
                'peso' => 0.2,
                'comprimento' => 40,
                'largura' => 5,
                'altura' => 1
            ];
        } elseif (str_contains($name, 'óculos')) {
            return [
                'peso' => 0.1,
                'comprimento' => 20,
                'largura' => 15,
                'altura' => 5
            ];
        } else {
            // Dimensões padrão para produtos não identificados
            return [
                'peso' => 0.3,
                'comprimento' => 25,
                'largura' => 20,
                'altura' => 3
            ];
        }
    }

    /**
     * Gera tags baseadas no tipo de produto
     */
    private function generateTags(string $name): array
    {
        $tags = [];
        
        if (str_contains($name, 'camiseta')) {
            $tags = array_merge($tags, ['camiseta', 'básica', 'algodão']);
        } elseif (str_contains($name, 'calça')) {
            $tags = array_merge($tags, ['calça', 'jeans', 'casual']);
        } elseif (str_contains($name, 'vestido')) {
            $tags = array_merge($tags, ['vestido', 'feminino', 'elegante']);
        } elseif (str_contains($name, 'tênis')) {
            $tags = array_merge($tags, ['tênis', 'esportivo', 'conforto']);
        } elseif (str_contains($name, 'jaqueta')) {
            $tags = array_merge($tags, ['jaqueta', 'casaco', 'inverno']);
        }

        // Adicionar tags gerais
        $tags = array_merge($tags, ['roupa', 'moda', 'qualidade']);
        
        return array_unique($tags);
    }
}