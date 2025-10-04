<?php

namespace Database\Factories;

use App\Models\Asisten;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Asisten>
 */
class AsistenFactory extends Factory
{
    protected $model = Asisten::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'kode' => strtoupper($this->faker->unique()->lexify('???')),
            'password' => Hash::make('cobaaja'),
            'role_id' => Role::factory(),
            'deskripsi' => $this->faker->sentence(),
            'nomor_telepon' => $this->faker->phoneNumber(),
            'id_line' => $this->faker->userName(),
            'instagram' => $this->faker->userName(),
        ];
    }
}
