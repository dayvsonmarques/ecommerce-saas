<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administradores', 'is_active' => true],
            ['name' => 'Owner', 'slug' => 'owner', 'description' => 'Proprietários', 'is_active' => true],
            ['name' => 'Seller', 'slug' => 'seller', 'description' => 'Vendedores', 'is_active' => true],
            ['name' => 'Customer', 'slug' => 'customer', 'description' => 'Clientes', 'is_active' => true],
        ];

        foreach ($items as $data) {
            Group::query()->firstOrCreate(['name' => $data['name']], $data);
        }
    }
}


