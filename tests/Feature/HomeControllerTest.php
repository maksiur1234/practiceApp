<?php
namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWithAuthenticatedUser()
    {
        // Stwórz użytkownika
        $user = User::factory()->create();

        // Zaloguj użytkownika
        $this->actingAs($user);

        // Wykonaj żądanie GET na ścieżkę /home
        $response = $this->get('/home');

        // Sprawdź status odpowiedzi
        $response->assertStatus(200);

        // Sprawdź zawartość odpowiedzi JSON
        $response->assertJson([
            'user' => $user->toArray(),
            'companyName' => $user->company ? $user->company->companyName : null
        ]);
    }

    public function testIndexWithoutAuthenticatedUser()
    {
        // Wykonaj żądanie do akcji index bez uwierzytelnionego użytkownika
        $response = $this->getJson('/home');

        // Sprawdź status odpowiedzi
        $response->assertUnauthorized();
    }
}
