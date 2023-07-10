<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;

class CalenderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Event::factory()->create([
            'event_name' => 'Event 1',
            'event_start' => '2023-07-10',
            'event_end' => '2023-07-12',
        ]);

        $response = $this->getJson('/calendar-event', [
            'start' => '2023-07-09',
            'end' => '2023-07-13',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'event_name',
                'event_start',
                'event_end',
            ],
        ]);
    }
}
