<?php
<<<<<<< HEAD

=======
>>>>>>> origin/main
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
<<<<<<< HEAD
    }

    public function index()
    {
        $user = Auth::user();
        $companyName = $user->company ? $user->company->companyName : null;

        // return response()->json(['user' => $user, 'companyName' => $companyName], 200);
        return view('home', ['user' => $user, 'companyName' => $companyName]);
=======
        }

        public function index()
        {
        $user = Auth::user();
        $companyName = $user->company ? $user->company->companyName : null;

        return response()->json(['user' => $user, 'companyName' => $companyName], 200);
>>>>>>> origin/main
    }
}
