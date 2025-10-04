<?php

namespace Database\Factories;

use App\Models\Modul;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Modul>
 */
class ModulFactory extends Factory
{
    protected $model = Modul::class;

    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(),
            'isi' => $this->faker->randomHtml(2, 3),
            'isEnglish' => $this->faker->boolean(20),
            'isUnlocked' => true,
        ];
    }
}
