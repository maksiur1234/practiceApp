<?php
namespace Database\Factories;

use App\Models\Event;
use App\Models\Company;
use App\Models\User;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $type = Type::factory()->create();

        return [
            'name' => 'Visit Request',
            'status' => 'pending',
            'date' => $this->faker->date(),
            'company_id' => $company->id,
            'type_id' => $type->id,
            'user_id' => $user->id,
        ];
    }
}


