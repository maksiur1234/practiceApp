<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        // Przygotowanie danych dla testu
        $company = Company::factory()->create(); // Stworzenie przykładowej firmy

        // Wykonanie żądania GET na endpoint '/companies/{company}' z podanym identyfikatorem firmy
        $response = $this->getJson("/companies/{$company->id}");

        // Sprawdzenie statusu odpowiedzi
        $response->assertStatus(200);

        // Sprawdzenie, czy odpowiedź JSON zawiera oczekiwane dane firmy
        $response->assertJson([
        'company' => [
        'id' => $company->id,
        'companyName' => $company->companyName,
        'email' => $company->email,
        ],
        ]);
    }

    public function testStore()
    {
        // Przygotowanie danych dla żądania POST
        $data = [
            'companyName' => 'Test Company',
            'email' => 'test@example.com',
            'password' => 'password123', // Hasło z co najmniej 8 znakami
            'type_id' => 999, // Nieprawidłowy identyfikator typu
        ];

        // Wykonanie żądania POST na endpoint '/companies' z danymi firmy
        $response = $this->postJson('/companies', $data);

        // Sprawdzenie statusu odpowiedzi
        $response->assertStatus(422);

        // Sprawdzenie, czy odpowiedź JSON zawiera oczekiwane dane błędu hasła i typu
        $response->assertJson([
            'message' => 'The selected type id is invalid.',
            'errors' => [
                'type_id' => [
                    'The selected type id is invalid.'
                ]
            ]
        ]);
    }

    public function testCreate()
    {
        // Wykonanie żądania GET na endpoint '/create-company'
        $response = $this->get('/create-company');

        // Sprawdzenie statusu odpowiedzi
        $response->assertStatus(200);

        // Sprawdzenie, czy odpowiedź JSON zawiera oczekiwane dane typów firm
        $response->assertJsonStructure([
            'types'
        ]);

        // Sprawdzenie, czy odpowiedź JSON zawiera prawidłową liczbę typów firm
        $response->assertJsonCount(Type::count(), 'types');
    }
}
