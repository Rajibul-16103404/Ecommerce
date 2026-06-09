<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'vendor_id' => User::factory()->state(['role' => 'vendor']),
            'name' => ucfirst($name),
            'slug' => str()->slug($name),
            'description' => $this->faker->paragraphs(2, true),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'discount_price' => $this->faker->boolean(30) ? $this->faker->randomFloat(2, 5, 450) : null,
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
