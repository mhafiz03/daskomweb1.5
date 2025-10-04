<?php

namespace Database\Factories;

use App\Models\Modul;
use App\Models\SoalMandiri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalMandiri>
 */
class SoalMandiriFactory extends Factory
{
    protected $model = SoalMandiri::class;

    public function definition(): array
    {
        return [
            'modul_id' => Modul::factory(),
            'soal' => $this->faker->realText(140),
        ];
    }
}
