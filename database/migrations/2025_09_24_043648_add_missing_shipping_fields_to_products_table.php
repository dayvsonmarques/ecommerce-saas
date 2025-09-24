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
            // Adicionar apenas os campos que faltam
            if (!Schema::hasColumn('products', 'marca')) {
                $table->string('marca')->nullable()->after('sku')->comment('Marca do produto');
            }
            if (!Schema::hasColumn('products', 'tamanhos')) {
                $table->json('tamanhos')->nullable()->after('material')->comment('Tamanhos disponíveis');
            }
            if (!Schema::hasColumn('products', 'cores')) {
                $table->json('cores')->nullable()->after('tamanhos')->comment('Cores disponíveis');
            }
            if (!Schema::hasColumn('products', 'estoque_minimo')) {
                $table->integer('estoque_minimo')->default(0)->after('stock')->comment('Estoque mínimo');
            }
            if (!Schema::hasColumn('products', 'controlar_estoque')) {
                $table->boolean('controlar_estoque')->default(true)->after('estoque_minimo')->comment('Controlar estoque');
            }
            if (!Schema::hasColumn('products', 'meta_titulo')) {
                $table->string('meta_titulo')->nullable()->after('is_active')->comment('Meta título para SEO');
            }
            if (!Schema::hasColumn('products', 'meta_descricao')) {
                $table->text('meta_descricao')->nullable()->after('meta_titulo')->comment('Meta descrição para SEO');
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable()->after('meta_descricao')->comment('Tags do produto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'marca', 'tamanhos', 'cores', 'estoque_minimo', 
                'controlar_estoque', 'meta_titulo', 'meta_descricao', 'tags'
            ]);
        });
    }
};