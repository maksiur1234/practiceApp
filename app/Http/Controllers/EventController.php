<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Company;
use App\Models\Type;

class EventController extends Controller
{
    public function create()
    {
        $types = Type::all();
        if (request()->expectsJson()) {
            $companies = [];
            return response()->json(['types' => $types, 'companies' => $companies], 200);
        }
        return view('events.create', compact('types'));
    }

    public function getCompaniesByType(Request $request)
    {
        $typeId = $request->input('type_id');
        $companies = Company::where('type_id', $typeId)->get();

        return view('events.companies', compact('companies'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'event_start' => 'required|date',
            'company_id' => 'required|exists:companies,id',
        ]);

        try {
            $event = new Event();
            $event->event_name = $request->input('event_name');
            $event->event_start = $request->input('event_start');
            $event->company_id = $request->input('company_id');
            $event->save();

            return response()->json(['message' => 'New event created successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save event to the database.'], 500);
        }
    }
}
