<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }

    public function checkout()
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
           'line_items' => [
               [
             'price_data' => [
                'currency' => 'pln',
                 'product_data' => [
                     'name' => 'Pay for the visit!',
                 ],
                 'unit_amount' => 500, //it equals to 5 pounds
             ],
               'quantity' => 1,
            ],
           ],
            'mode' => 'payment',
            'success_url' => route('success'),
            'cancel_url' => route('home'),
        ]);

        return redirect()->away($session->url);
    }

    public function success()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }
}
