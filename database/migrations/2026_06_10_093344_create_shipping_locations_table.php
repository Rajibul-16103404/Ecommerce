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
        Schema::create('shipping_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('fee', 10, 2);
            $table->timestamps();
        });

        // Seed default locations
        \Illuminate\Support\Facades\DB::table('shipping_locations')->insert([
            [
                'name' => 'Dhaka',
                'fee' => 60.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Outside Dhaka',
                'fee' => 120.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_locations');
    }
};
