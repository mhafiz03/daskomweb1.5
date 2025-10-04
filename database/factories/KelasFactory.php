<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition(): array
    {
        return [
            'hari' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']),
            'shift' => $this->faker->numberBetween(1, 4),
            'kelas' => strtoupper($this->faker->bothify('EL-##-##')),
            'totalGroup' => $this->faker->numberBetween(3, 8),
        ];
    }
}
