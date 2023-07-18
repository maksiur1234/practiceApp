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
        $user = Auth::user();
        if (request()->expectsJson()) {
            return response()->json(['company' => $company], 200);
        }
        return view('companies.show', ['company' => $company, 'user' => $user]);
    }

    public function create()
    {
        $types = Type::all();
        if (request()->expectsJson()) {
            return response()->json(['types' => $types], 200);
        }
        return view('companies.create', ['types' => $types]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $user = Auth::user();

        $company = new Company();
        $company->companyName = $request->input('companyName');
        $company->email = $request->input('email');
        $company->password = bcrypt($request->input('password'));
        $company->type_id = $request->input('type_id'); // Assign the type_id from the request
        $company->user_id = $user->username;
        $company->media_url = 'example.com';
        $company->save();

        return response()->json(['name' => $company->companyName, 'id' => $company->id]);
        //return redirect('/home')->with('success', 'Firma została utworzona pomyślnie!');
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

        return response()->json(['message' => 'Media uploaded successfully!'], 200);
        //return redirect()->back()->with('success', 'Media uploaded successfully!');
    }

}
