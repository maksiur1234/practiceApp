<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertNoContent();
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->postJson('/login', [
            'email' => 'invalid@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'These credentials do not match our records.',
            ]);
    }

    public function testRegisterWithValidData()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(201);
    }


    public function testRegisterWithInvalidData()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'differentpassword',
        ];

        $response = $this->postJson('/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function testLogoutWithAuthenticatedUser()
    {
        // Utwórz użytkownika i zaloguj go
        $user = User::factory()->create();
        Auth::guard('web')->login($user);

        // Wykonaj żądanie wylogowania
        $response = $this->postJson('/logout');

        // Sprawdź status odpowiedzi
        $response->assertStatus(204);

        // Sprawdź, czy użytkownik został wylogowany
        $this->assertFalse(Auth::guard('web')->check());
    }

    public function testLogoutWithoutAuthenticatedUser()
    {
        // Wykonaj żądanie wylogowania bez uwierzytelnionego użytkownika
        $response = $this->postJson('/logout');

        // Sprawdź status odpowiedzi
        $response->assertStatus(204);
    }
}
