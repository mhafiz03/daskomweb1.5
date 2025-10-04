<?php

namespace Database\Factories;

use App\Models\Modul;
use App\Models\SoalFitb;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalFitb>
 */
class SoalFitbFactory extends Factory
{
    protected $model = SoalFitb::class;

    public function definition(): array
    {
        return [
            'modul_id' => Modul::factory(),
            'soal' => $this->faker->realText(120),
        ];
    }
}
