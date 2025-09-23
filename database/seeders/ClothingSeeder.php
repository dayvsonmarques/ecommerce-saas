<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ClothingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar categorias de roupas
        $categories = [
            ['name' => 'Camisetas', 'description' => 'Camisetas masculinas e femininas', 'is_active' => true],
            ['name' => 'Calças', 'description' => 'Calças jeans, sociais e casuais', 'is_active' => true],
            ['name' => 'Vestidos', 'description' => 'Vestidos femininos para todas as ocasiões', 'is_active' => true],
            ['name' => 'Blusas', 'description' => 'Blusas e camisas femininas', 'is_active' => true],
            ['name' => 'Jaquetas', 'description' => 'Jaquetas e casacos', 'is_active' => true],
            ['name' => 'Shorts', 'description' => 'Shorts masculinos e femininos', 'is_active' => true],
            ['name' => 'Saias', 'description' => 'Saias femininas', 'is_active' => true],
            ['name' => 'Suéteres', 'description' => 'Suéteres e blusas de frio', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Buscar as categorias criadas
        $camisetas = Category::where('name', 'Camisetas')->first();
        $calcas = Category::where('name', 'Calças')->first();
        $vestidos = Category::where('name', 'Vestidos')->first();
        $blusas = Category::where('name', 'Blusas')->first();
        $jaquetas = Category::where('name', 'Jaquetas')->first();
        $shorts = Category::where('name', 'Shorts')->first();
        $saias = Category::where('name', 'Saias')->first();
        $sueteres = Category::where('name', 'Suéteres')->first();

        // Produtos de roupas com imagens
        $products = [
            // Camisetas
            ['name' => 'Camiseta Básica Preta', 'description' => 'Camiseta 100% algodão, corte clássico', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop', 'price' => 29.90, 'stock' => 50, 'category_id' => $camisetas->id],
            ['name' => 'Camiseta Polo Azul', 'description' => 'Polo masculina com gola, tecido piqué', 'image' => 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=400&h=400&fit=crop', 'price' => 79.90, 'stock' => 30, 'category_id' => $camisetas->id],
            ['name' => 'Camiseta Estampada', 'description' => 'Camiseta com estampa exclusiva', 'image' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=400&h=400&fit=crop', 'price' => 39.90, 'stock' => 25, 'category_id' => $camisetas->id],
            ['name' => 'Camiseta Regata Branca', 'description' => 'Regata feminina para verão', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 24.90, 'stock' => 40, 'category_id' => $camisetas->id],
            ['name' => 'Camiseta Gola V', 'description' => 'Camiseta com decote em V, conforto total', 'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400&h=400&fit=crop', 'price' => 34.90, 'stock' => 35, 'category_id' => $camisetas->id],
            ['name' => 'Camiseta Longa', 'description' => 'Camiseta oversized, estilo streetwear', 'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a1af?w=400&h=400&fit=crop', 'price' => 49.90, 'stock' => 20, 'category_id' => $camisetas->id],

            // Calças
            ['name' => 'Jeans Skinny Azul', 'description' => 'Calça jeans skinny, modelagem moderna', 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop', 'price' => 129.90, 'stock' => 25, 'category_id' => $calcas->id],
            ['name' => 'Calça Social Preta', 'description' => 'Calça social masculina, tecido premium', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 159.90, 'stock' => 15, 'category_id' => $calcas->id],
            ['name' => 'Jeans Boyfriend', 'description' => 'Calça jeans boyfriend feminina', 'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=400&h=400&fit=crop', 'price' => 119.90, 'stock' => 30, 'category_id' => $calcas->id],
            ['name' => 'Calça Cargo Verde', 'description' => 'Calça cargo com bolsos laterais', 'image' => 'https://images.unsplash.com/photo-1506629905607-2b0b4b0b0b0b?w=400&h=400&fit=crop', 'price' => 89.90, 'stock' => 20, 'category_id' => $calcas->id],
            ['name' => 'Calça Legging Preta', 'description' => 'Legging de alta compressão', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 59.90, 'stock' => 40, 'category_id' => $calcas->id],
            ['name' => 'Calça Wide Leg', 'description' => 'Calça wide leg, tendência 2024', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 99.90, 'stock' => 18, 'category_id' => $calcas->id],

            // Vestidos
            ['name' => 'Vestido Midi Floral', 'description' => 'Vestido midi com estampa floral', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 149.90, 'stock' => 12, 'category_id' => $vestidos->id],
            ['name' => 'Vestido Longo Elegante', 'description' => 'Vestido longo para ocasiões especiais', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 299.90, 'stock' => 8, 'category_id' => $vestidos->id],
            ['name' => 'Vestido Curto Casual', 'description' => 'Vestido curto para o dia a dia', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 79.90, 'stock' => 22, 'category_id' => $vestidos->id],
            ['name' => 'Vestido Tubinho Preto', 'description' => 'Vestido tubinho clássico', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 119.90, 'stock' => 15, 'category_id' => $vestidos->id],
            ['name' => 'Vestido Midi Listrado', 'description' => 'Vestido midi com listras verticais', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 89.90, 'stock' => 20, 'category_id' => $vestidos->id],
            ['name' => 'Vestido Maxi Estampado', 'description' => 'Vestido maxi com estampa tropical', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 179.90, 'stock' => 10, 'category_id' => $vestidos->id],

            // Blusas
            ['name' => 'Blusa de Seda Branca', 'description' => 'Blusa de seda, elegante e confortável', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 199.90, 'stock' => 8, 'category_id' => $blusas->id],
            ['name' => 'Camisa Social Branca', 'description' => 'Camisa social masculina, tecido oxford', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 139.90, 'stock' => 12, 'category_id' => $blusas->id],
            ['name' => 'Blusa Transparente', 'description' => 'Blusa transparente com forro', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 69.90, 'stock' => 25, 'category_id' => $blusas->id],
            ['name' => 'Camisa Xadrez', 'description' => 'Camisa xadrez, estilo casual', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 89.90, 'stock' => 18, 'category_id' => $blusas->id],
            ['name' => 'Blusa Cropped', 'description' => 'Blusa cropped, tendência jovem', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 49.90, 'stock' => 30, 'category_id' => $blusas->id],
            ['name' => 'Camisa de Linho', 'description' => 'Camisa de linho, fresca para o verão', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 109.90, 'stock' => 15, 'category_id' => $blusas->id],

            // Jaquetas
            ['name' => 'Jaqueta Jeans Clássica', 'description' => 'Jaqueta jeans, peça atemporal', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 189.90, 'stock' => 20, 'category_id' => $jaquetas->id],
            ['name' => 'Blazer Feminino', 'description' => 'Blazer elegante para escritório', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 249.90, 'stock' => 10, 'category_id' => $jaquetas->id],
            ['name' => 'Jaqueta de Couro', 'description' => 'Jaqueta de couro legítimo', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 599.90, 'stock' => 5, 'category_id' => $jaquetas->id],
            ['name' => 'Casaco de Lã', 'description' => 'Casaco de lã para inverno', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 179.90, 'stock' => 15, 'category_id' => $jaquetas->id],
            ['name' => 'Jaqueta Bomber', 'description' => 'Jaqueta bomber, estilo militar', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 159.90, 'stock' => 18, 'category_id' => $jaquetas->id],
            ['name' => 'Blazer Masculino', 'description' => 'Blazer masculino, corte clássico', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 299.90, 'stock' => 8, 'category_id' => $jaquetas->id],

            // Shorts
            ['name' => 'Short Jeans Masculino', 'description' => 'Short jeans masculino, corte reto', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 79.90, 'stock' => 25, 'category_id' => $shorts->id],
            ['name' => 'Short Esportivo', 'description' => 'Short esportivo para academia', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 49.90, 'stock' => 35, 'category_id' => $shorts->id],
            ['name' => 'Short Feminino High Waist', 'description' => 'Short feminino cintura alta', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 59.90, 'stock' => 30, 'category_id' => $shorts->id],
            ['name' => 'Bermuda Cargo', 'description' => 'Bermuda cargo com bolsos', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 69.90, 'stock' => 22, 'category_id' => $shorts->id],
            ['name' => 'Short de Banho', 'description' => 'Short de banho masculino', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 39.90, 'stock' => 40, 'category_id' => $shorts->id],
            ['name' => 'Short Social', 'description' => 'Short social para ocasiões casuais', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 89.90, 'stock' => 15, 'category_id' => $shorts->id],

            // Saias
            ['name' => 'Saia Midi Plissada', 'description' => 'Saia midi plissada, elegante', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 99.90, 'stock' => 20, 'category_id' => $saias->id],
            ['name' => 'Saia Longa Floral', 'description' => 'Saia longa com estampa floral', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 119.90, 'stock' => 15, 'category_id' => $saias->id],
            ['name' => 'Saia Curta Jeans', 'description' => 'Saia curta de jeans', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 79.90, 'stock' => 25, 'category_id' => $saias->id],
            ['name' => 'Saia Lápis Preta', 'description' => 'Saia lápis clássica', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 89.90, 'stock' => 18, 'category_id' => $saias->id],
            ['name' => 'Saia Asimétrica', 'description' => 'Saia asimétrica, design moderno', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 109.90, 'stock' => 12, 'category_id' => $saias->id],
            ['name' => 'Saia de Couro', 'description' => 'Saia de couro sintético', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 149.90, 'stock' => 8, 'category_id' => $saias->id],

            // Suéteres
            ['name' => 'Suéter de Lã', 'description' => 'Suéter de lã, conforto e elegância', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 159.90, 'stock' => 15, 'category_id' => $sueteres->id],
            ['name' => 'Moletom com Capuz', 'description' => 'Moletom com capuz, casual', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 129.90, 'stock' => 25, 'category_id' => $sueteres->id],
            ['name' => 'Cardigã Feminino', 'description' => 'Cardigã feminino, versátil', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 139.90, 'stock' => 18, 'category_id' => $sueteres->id],
            ['name' => 'Suéter Gola Alta', 'description' => 'Suéter gola alta, inverno', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 119.90, 'stock' => 20, 'category_id' => $sueteres->id],
            ['name' => 'Moletom Oversized', 'description' => 'Moletom oversized, conforto total', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 99.90, 'stock' => 22, 'category_id' => $sueteres->id],
            ['name' => 'Suéter de Cashmere', 'description' => 'Suéter de cashmere, luxo', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 399.90, 'stock' => 5, 'category_id' => $sueteres->id],
            ['name' => 'Cardigã Masculino', 'description' => 'Cardigã masculino, elegante', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 179.90, 'stock' => 12, 'category_id' => $sueteres->id],
            ['name' => 'Suéter Listrado', 'description' => 'Suéter listrado, clássico', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 89.90, 'stock' => 28, 'category_id' => $sueteres->id],
            ['name' => 'Moletom Zíper', 'description' => 'Moletom com zíper frontal', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 109.90, 'stock' => 16, 'category_id' => $sueteres->id],
            ['name' => 'Suéter de Alpaca', 'description' => 'Suéter de alpaca, premium', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 'price' => 299.90, 'stock' => 6, 'category_id' => $sueteres->id],
        ];

        foreach ($products as $productData) {
            $productData['is_active'] = true;
            Product::create($productData);
        }
    }
}