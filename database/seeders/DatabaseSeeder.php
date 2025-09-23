<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Garantir um usuário Admin
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'Admin',
            ]
        );

        // Executar seeders de domínio
        $this->call([
            GroupSeeder::class,
            ClothingSeeder::class,
        ]);

        // Vincular admin ao grupo Admin, se existir
        $adminUser = User::where('email','admin@example.com')->first();
        $adminGroup = Group::where('slug','admin')->first();
        if ($adminUser && $adminGroup) {
            if (!$adminUser->groups()->where('groups.id', $adminGroup->id)->exists()) {
                $adminUser->groups()->attach($adminGroup->id);
            }
        }
    }
}
