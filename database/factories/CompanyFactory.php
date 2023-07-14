<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'companyName' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'type_id' => 1,
        ];
    }
}
