<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $size = '400/400';
        Product::chunk(100, function ($products) use ($size) {
            foreach ($products as $product) {
                $seed = $product->id ?: md5($product->name);
                $url = "https://picsum.photos/seed/product-{$seed}/{$size}";
                if ($product->image !== $url) {
                    $product->image = $url;
                    $product->save();
                }
            }
        });
    }
}
