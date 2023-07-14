<?php
namespace Database\Factories;

use App\Models\Event;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();

        return [
            'event_name' => 'Visit Request',
            'event_start' => $this->faker->dateTime(),
            'event_end' => $this->faker->dateTime(),
            'company_id' => $company->id,
            'user_id' => $user->id,
            'visit_date' => $this->faker->date(),
        ];
    }
}


