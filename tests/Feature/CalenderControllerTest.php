<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CalenderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testSendVisitRequest()
    {
        // Przygotowanie danych testowych
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $requestData = [
            'user_id' => $user->id,
            'company_id' => $company->id,
            'visit_date' => '2023-07-11',
        ];

        // Wywołanie żądania POST
        $response = $this->actingAs($user)
            ->get('/visit-request/{eventId}', $requestData);

        // Sprawdzanie odpowiedzi
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Request sent successfully',
            ]);

        // Sprawdzanie, czy zdarzenie zostało utworzone w bazie danych
        $this->assertDatabaseHas('events', [
            'user_id' => $user->id,
            'company_id' => $company->id,
            'visit_date' => '2023-07-11',
        ]);
    }

    public function testRejectVisitRequest()
    {
        // Tworzenie testowych danych
        $company = Company::factory()->create();
        $event = Event::factory()->create(['company_id' => $company->id]);

        // Tworzenie żądania z danymi
        $requestData = [
            'company_id' => $company->id,
        ];

        // Wywołanie metody rejectVisitRequest
        $response = $this->put('/visit-request/' . $event->id . '/reject', $requestData);

        // Sprawdzanie statusu odpowiedzi
        $response->assertStatus(200);

        // Sprawdzanie treści odpowiedzi
        $response->assertJson([
            'message' => 'Visit rejected',
        ]);

        // Sprawdzanie, czy zdarzenie zostało zaktualizowane
        $updatedEvent = Event::find($event->id);
        $this->assertEquals('Visit Rejected', $updatedEvent->event_name);
    }

    public function testRejectVisitRequestWithInvalidCompany()
    {
        // Tworzenie testowych danych
        $company = Company::factory()->create();
        $event = Event::factory()->create(['company_id' => $company->id]);

        // Tworzenie żądania z nieprawidłowymi danymi firmy
        $invalidCompanyId = 999; // Id firmy, która nie istnieje
        $requestData = [
            'company_id' => $invalidCompanyId,
        ];

        // Wywołanie metody rejectVisitRequest
        $response = $this->put('/visit-request/' . $event->id . '/reject', $requestData);

        // Sprawdzanie statusu odpowiedzi
        $response->assertStatus(302); // Poprawka: zmiana statusu odpowiedzi na 302

        // Sprawdzanie treści odpowiedzi
        $response->assertSessionHasErrors(['company_id']); // Poprawka: sprawdzanie błędów walidacji

        // Sprawdzanie, czy zdarzenie nie zostało zaktualizowane
        $updatedEvent = Event::find($event->id);
        $this->assertEquals('Visit Request', $updatedEvent->event_name);
    }

    public function testAcceptVisitRequest()
    {
        // Tworzenie testowych danych
        $company = Company::factory()->create();
        $event = Event::factory()->create(['company_id' => $company->id]);
        $requestData = ['company_id' => $company->id];

        // Wywołanie metody acceptVisitRequest
        $response = $this->put('/visit-request/' . $event->id . '/accept', $requestData);

        // Sprawdzanie statusu odpowiedzi
        $response->assertStatus(200);

        // Sprawdzanie treści odpowiedzi
        $response->assertJson(['message' => 'Visit accepted']);

        // Sprawdzanie, czy zdarzenie zostało zaktualizowane
        $updatedEvent = Event::find($event->id);
        $this->assertEquals('Visit Accepted', $updatedEvent->event_name);
    }

    public function testAcceptVisitRequestWithInvalidCompany()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $requestData = [
            'company_id' => 9999, // Nieprawidłowe ID firmy
        ];

        Auth::login($user); // Logowanie użytkownika

        $response = $this->put('/visit-request/{eventId}' . $event->id . '/accept', $requestData);

        $response->assertStatus(403)
            ->assertJson(['message' => 'You do not have permission for that!']);
    }
}
