<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('customer_id')->constrained()->onDelete('set null');
            $table->json('shipping_address')->nullable()->after('total_amount');
            $table->json('billing_address')->nullable()->after('shipping_address');
            $table->string('payment_method')->nullable()->after('billing_address');
            $table->text('notes')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'shipping_address', 'billing_address', 'payment_method', 'notes']);
        });
    }
};