<?php
namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
        'event_name' => $this->faker->sentence,
        'event_start' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        'event_end' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}
