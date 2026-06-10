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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_area')->nullable()->after('shipping_address');
            $table->string('shipping_landmark')->nullable()->after('shipping_area');
            $table->text('shipping_notes')->nullable()->after('shipping_zip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_area', 'shipping_landmark', 'shipping_notes']);
        });
    }
};
