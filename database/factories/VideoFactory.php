<?php

namespace Database\Factories;

use Core\Domain\Enum\Rating;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ratings = [Rating::L, Rating::ER, Rating::RATE10, Rating::RATE12, Rating::RATE14, Rating::RATE16, Rating::RATE18];

        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->name(),
            'description' => $this->faker->sentence(6),
            'year_launched' => $this->faker->year(),
            'opened' => $this->faker->boolean(),
            'rating' => $ratings[array_rand($ratings)],
            'duration' => $this->faker->numberBetween(1, 120),        
            'created_at' => now(),
        ];
    }
}
