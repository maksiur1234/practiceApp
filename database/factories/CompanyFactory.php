<?php

namespace Database\Factories;

use     App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'user_id' => $this->faker->randomNumber(),
        ];
    }
}
