<?php

namespace Database\Factories;

use App\Models\Modul;
use App\Models\SoalTk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalTk>
 */
class SoalTkFactory extends Factory
{
    protected $model = SoalTk::class;

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
