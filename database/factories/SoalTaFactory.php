<?php

namespace Database\Factories;

use App\Models\Modul;
use App\Models\SoalTa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalTa>
 */
class SoalTaFactory extends Factory
{
    protected $model = SoalTa::class;

    public function definition(): array
    {
        return [
            'modul_id' => Modul::factory(),
            'pertanyaan' => $this->faker->realText(120),
            'jawaban_benar' => $this->faker->sentence(),
            'jawaban_salah1' => $this->faker->sentence(),
            'jawaban_salah2' => $this->faker->sentence(),
            'jawaban_salah3' => $this->faker->sentence(),
        ];
    }
}
