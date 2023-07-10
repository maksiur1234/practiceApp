<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function show(Company $company)
    {
        return response()->json(['company' => $company], 200);
    }

    public function create()
    {
        $types = Type::all(); // Pobierz wszystkie typy firm

        return response()->json(['types' => $types], 200);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        // Get the logged-in user
        $user = Auth::user();

        // Create a new company based on the provided data
        $company = new Company();
        $company->companyName = $request->input('companyName');
        $company->email = $request->input('email');
        $company->password = bcrypt($request->input('password'));
        $company->type_id = $request->input('type_id'); // Assign the type_id from the request
        $company->companyOwner = $user->username;
        $company->save();

        // Redirect the user to the appropriate page after creating the company
        return response()->json(['name' => $company->companyName, 'id' => $company->id]);
        // return redirect('/home')->with('success', 'Company created successfully!');
    }
}
