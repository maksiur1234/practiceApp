<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CalendarFactory extends Factory
{
    protected $model = Event::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(1, 10));
        $endDate = $startDate->copy()->addHours($this->faker->numberBetween(1, 8));

        return [
            'event_name' => $this->faker->sentence,
            'event_start' => $startDate,
            'event_end' => $endDate,
        ];
    }
}
