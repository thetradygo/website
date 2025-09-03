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
        Schema::table('pos_carts', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('shop_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_carts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });
    }
};
