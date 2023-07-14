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
<<<<<<< HEAD
        $user = Auth::user();
        // return response()->json(['company' => $company], 200);
        return view('companies.show', ['company' => $company, 'user' => $user]);
=======
        return response()->json(['company' => $company], 200);
>>>>>>> origin/main
    }

    public function create()
    {
        $types = Type::all(); // Pobierz wszystkie typy firm

<<<<<<< HEAD
        // return response()->json(['types' => $types], 200);
        return view('companies.create', ['types' => $types]);
    }

    public function store(StoreCompanyRequest $request)
=======
        return response()->json(['types' => $types], 200);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
>>>>>>> origin/main
    {
        // Get the logged-in user
        $user = Auth::user();

        // Create a new company based on the provided data
        $company = new Company();
        $company->companyName = $request->input('companyName');
        $company->email = $request->input('email');
        $company->password = bcrypt($request->input('password'));
        $company->type_id = $request->input('type_id'); // Assign the type_id from the request
<<<<<<< HEAD
        $company->user_id = $user->username;
        $company->media_url = 'example.com';
        $company->save();

        // Redirect the user to the appropriate page after creating the company
        // return response()->json(['name' => $company->companyName, 'id' => $company->id]);
        return redirect('/home')->with('success', 'Firma została utworzona pomyślnie!');
    }

    public function uploadMedia(Request $request, $companyId)
    {
        $request->validate([
            'media' => 'required|file|image|max:2048',
        ]);

        $company = Company::findOrFail($companyId);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $filename = $file->getClientOriginalName();

            // Store the uploaded file in the storage/app/public directory
            $path = $file->storeAs('public', $filename);

            // Update the company's media_url path in the database
            $company->media_url = $path;
            $company->save();
        }

        return redirect()->back()->with('success', 'Media uploaded successfully!');
    }

=======
        $company->companyOwner = $user->username;
        $company->save();

        // Redirect the user to the appropriate page after creating the company
        return response()->json(['name' => $company->companyName, 'id' => $company->id]);
        // return redirect('/home')->with('success', 'Company created successfully!');
    }
>>>>>>> origin/main
}
