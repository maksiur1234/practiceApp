<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use App\Models\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
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
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            $company = new Company();
            $company->name = $request->input('name');
            $company->email = $request->input('email');
            $company->password = bcrypt($request->input('password'));
            $company->type_id = $request->input('type_id');

            // Assuming 'user_id' is the foreign key to the 'users' table, use $user->id
            $company->user_id = $user->id;

            $company->media_url = 'example.com';
            $company->save();

            $typeIds = $request->input('type_ids', []);
            $company->types()->sync($typeIds);

            return response()->json(['name' => $company->name, 'id' => $company->id]);
            //return redirect('/home')->with('success', 'Firma została utworzona pomyślnie!');
        } else {
            // User is not authenticated, handle the error or redirect to the login page
            return response()->json(['error' => 'User not authenticated']);
            // Or redirect to the login page:
            // return redirect('/login')->with('error', 'You need to log in first.');
        }
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

    public function index(Request $request): JsonResponse
    {
       $type = Type::findOrFail($request->input('type_id'));
//       dd($type);

        return response()->json(
            Company::query()
                ->whereHas('type', function(Builder $query) use ($type){
                    $query->where('name', $type->name);
                })
                ->get()
        );
    }
}
