<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Generate 5-8 images per product
            $imageCount = rand(5, 8);
            
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $this->generateImageUrl($product->id, $i),
                    'alt_text' => $product->name . ' - Imagem ' . ($i + 1),
                    'sort_order' => $i,
                    'is_primary' => $i === 0, // First image is primary
                ]);
            }
        }
    }

    private function generateImageUrl($productId, $imageIndex)
    {
        // Use different image services for variety
        $services = [
            'https://picsum.photos/800/800?random=' . ($productId * 10 + $imageIndex),
            'https://images.unsplash.com/photo-' . (1500000000000 + $productId * 100 + $imageIndex) . '?w=800&h=800&fit=crop',
            'https://via.placeholder.com/800x800/6366f1/ffffff?text=Product+' . $productId . '+' . ($imageIndex + 1),
        ];

        return $services[$imageIndex % count($services)];
    }
}
