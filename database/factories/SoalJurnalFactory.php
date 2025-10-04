<?php

namespace Database\Factories;

use App\Models\Modul;
use App\Models\SoalJurnal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalJurnal>
 */
class SoalJurnalFactory extends Factory
{
    protected $model = SoalJurnal::class;

    public function definition(): array
    {
        return [
            'modul_id' => Modul::factory(),
            'soal' => $this->faker->realText(150),
            'sulit' => $this->faker->boolean(20),
        ];
    }
}
