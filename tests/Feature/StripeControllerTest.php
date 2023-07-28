<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\StripeController;
use Stripe\Stripe;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Stripe\Checkout\Session;

class StripeControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->get(route('stripe.index'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('user', $user);
    }

    public function testCheckoutMethotdRedirectsToStripeCheckout()
    {
        // create mock for class \Stripe\Stripe and \Stripe\Checkout\Session
        $stripeMock = Mockery::mock(\Stripe\Stripe::class);
        $stripeMock->shouldReceive('setApiKey')->once();

        $stripeCheckoutSessionMock = Mockery::mock(\Stripe\Checkout\Session::class);
        $stripeCheckoutSessionMock->shouldReceive('create')->once()->andReturn([
            'url' => 'https://example.com/stripe_checkout_url'
        ]);

        // inject mock into app
        $this->app->instance(\Stripe\Stripe::class, $stripeMock);
        $this->app->instance(\Stripe\Checkout\Session::class, $stripeCheckoutSessionMock);

        // invoke method checkout()
        $response = $this->get(route('checkout'));

        // check if answer redirect to correct uri
        $response->assertRedirect('https://example.com/stripe_checkout_url');
    }
}
