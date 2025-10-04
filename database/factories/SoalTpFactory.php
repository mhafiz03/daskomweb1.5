<?php

namespace Database\Factories;

use App\Models\Modul;
use App\Models\SoalTp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalTp>
 */
class SoalTpFactory extends Factory
{
    protected $model = SoalTp::class;

    public function definition(): array
    {
        return [
            'modul_id' => Modul::factory(),
            'soal' => $this->faker->realText(160),
            'isEssay' => $this->faker->boolean(30),
            'isProgram' => $this->faker->boolean(20),
        ];
    }
}
