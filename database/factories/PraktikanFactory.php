<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Praktikan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Praktikan>
 */
class PraktikanFactory extends Factory
{
    protected $model = Praktikan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nim' => '110217' . $this->faker->numberBetween(1000, 9999),
            'password' => Hash::make('cobaaja'),
            'kelas_id' => Kelas::factory(),
            'alamat' => $this->faker->address(),
            'nomor_telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
