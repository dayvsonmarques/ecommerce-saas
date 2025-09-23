<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('groups')) {
            Schema::table('groups', function (Blueprint $table) {
                if (!Schema::hasColumn('groups', 'slug')) {
                    $table->string('slug')->default('')->after('name');
                }
                if (!Schema::hasColumn('groups', 'description')) {
                    $table->string('description')->nullable()->after('slug');
                }
                if (!Schema::hasColumn('groups', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('groups')) {
            Schema::table('groups', function (Blueprint $table) {
                if (Schema::hasColumn('groups', 'is_active')) {
                    $table->dropColumn('is_active');
                }
                if (Schema::hasColumn('groups', 'description')) {
                    $table->dropColumn('description');
                }
            });
        }
    }
};


