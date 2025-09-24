<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('peso', 8, 3)->default(0.3)->after('price')->comment('Peso em kg');
            $table->decimal('comprimento', 8, 2)->default(20)->after('peso')->comment('Comprimento em cm');
            $table->decimal('largura', 8, 2)->default(15)->after('comprimento')->comment('Largura em cm');
            $table->decimal('altura', 8, 2)->default(5)->after('largura')->comment('Altura em cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['peso', 'comprimento', 'largura', 'altura']);
        });
    }
};