<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Company;
use App\Models\Type;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function create()
    {
        $types = Type::all();
        session()->forget('selected_type');
        session()->forget('selected_companies');
        return view('events.create', compact('types'));
    }

    public function getCompaniesByType(Request $request)
    {
        $typeId = $request->input('type_id');
        $companies = Company::where('type_id', $typeId)->get();

        return response()->json(['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_id' => 'required|exists:types,id',
            'event_name' => 'required',
            'event_start' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'event_end' => 'nullable|date',
            'event_status' => 'nullable',
        ]);

        try {
            $data = $request->only([
                'event_name',
                'event_start',
                'company_id',
                'event_end',
                'event_status',
            ]);

            $event = Event::create($data);

            return new JsonResponse(['message' => 'New event created successfully!', 'data' => $event], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to save event to the database.'], 500);
        }
    }


    public function selectCompanies(Request $request)
    {
        $request->validate([
            'type_id' => 'required|exists:types,id',
        ]);

        session(['selected_type' => $request->input('type_id')]);
        session()->forget('selected_companies');

        return redirect()->route('events.chooseCompanyAndDate');
    }

    public function chooseCompanyAndDate()
    {
        $selectedType = session('selected_type');

        if (!$selectedType) {
            return redirect()->route('events.create')->withErrors(['error' => 'Please select a type first.']);
        }

        $companies = Company::where('type_id', $selectedType)->get();
        session(['selected_companies' => $companies]);

        return view('events.choose_company_and_date', compact('companies'));
    }
}
