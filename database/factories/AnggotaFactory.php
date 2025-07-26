<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nis' => $this->faker->randomNumber(8, false),
            'nama' => $this->faker->name(),
            'kelas' => $this->faker->randomElement(['VII', 'VIII', 'IX']),
            'jk' => $this->faker->randomElement(['L', 'P']),
            'status' => $this->faker->randomElement(['draft', 'aktif', 'keluar', 'tolak']),
            'ekskul_id' => $this->faker->randomElement([3, 2]),
            "hp" => $this->faker->phoneNumber,
            "email" => $this->faker->safeEmail,
            'alamat' => $this->faker->address,
        ];
    }
}
