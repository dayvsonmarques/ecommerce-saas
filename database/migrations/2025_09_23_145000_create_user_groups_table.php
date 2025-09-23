<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_groups')) {
            Schema::create('user_groups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('group_id');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['user_id','group_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};


