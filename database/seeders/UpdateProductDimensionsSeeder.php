<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductDimensionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Gerar dimensões realistas baseadas no tipo de produto
            $dimensions = $this->generateRealisticDimensions($product->name);
            
            $product->update([
                'peso' => $dimensions['peso'],
                'comprimento' => $dimensions['comprimento'],
                'largura' => $dimensions['largura'],
                'altura' => $dimensions['altura']
            ]);
        }

        $this->command->info('Dimensões dos produtos atualizadas com sucesso!');
    }

    /**
     * Gera dimensões realistas baseadas no nome do produto
     */
    private function generateRealisticDimensions(string $productName): array
    {
        $name = strtolower($productName);

        // Roupas
        if (str_contains($name, 'camiseta') || str_contains($name, 'camisa') || str_contains($name, 'blusa')) {
            return [
                'peso' => 0.2,
                'comprimento' => 30,
                'largura' => 25,
                'altura' => 2
            ];
        }

        if (str_contains($name, 'calça') || str_contains($name, 'jeans') || str_contains($name, 'pantalon')) {
            return [
                'peso' => 0.6,
                'comprimento' => 35,
                'largura' => 30,
                'altura' => 3
            ];
        }

        if (str_contains($name, 'vestido') || str_contains($name, 'saia')) {
            return [
                'peso' => 0.4,
                'comprimento' => 40,
                'largura' => 30,
                'altura' => 2
            ];
        }

        if (str_contains($name, 'jaqueta') || str_contains($name, 'casaco') || str_contains($name, 'blazer')) {
            return [
                'peso' => 0.8,
                'comprimento' => 45,
                'largura' => 35,
                'altura' => 4
            ];
        }

        if (str_contains($name, 'short') || str_contains($name, 'bermuda')) {
            return [
                'peso' => 0.3,
                'comprimento' => 30,
                'largura' => 25,
                'altura' => 2
            ];
        }

        if (str_contains($name, 'meia') || str_contains($name, 'sock')) {
            return [
                'peso' => 0.05,
                'comprimento' => 20,
                'largura' => 15,
                'altura' => 1
            ];
        }

        if (str_contains($name, 'sapato') || str_contains($name, 'tênis') || str_contains($name, 'bota')) {
            return [
                'peso' => 0.8,
                'comprimento' => 35,
                'largura' => 25,
                'altura' => 15
            ];
        }

        if (str_contains($name, 'bolsa') || str_contains($name, 'mochila') || str_contains($name, 'carteira')) {
            return [
                'peso' => 0.5,
                'comprimento' => 30,
                'largura' => 20,
                'altura' => 10
            ];
        }

        if (str_contains($name, 'cinto') || str_contains($name, 'belt')) {
            return [
                'peso' => 0.2,
                'comprimento' => 40,
                'largura' => 5,
                'altura' => 1
            ];
        }

        if (str_contains($name, 'óculos') || str_contains($name, 'glasses')) {
            return [
                'peso' => 0.1,
                'comprimento' => 20,
                'largura' => 15,
                'altura' => 5
            ];
        }

        // Dimensões padrão para produtos não identificados
        return [
            'peso' => 0.3,
            'comprimento' => 25,
            'largura' => 20,
            'altura' => 3
        ];
    }
}