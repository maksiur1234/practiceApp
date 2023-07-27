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
            'company_id' => 'required|exists:companies,id',
            'name' => 'required',
            'date' => 'required|date',
            'status' => 'nullable',
        ]);

        $data = $request->only([
            'name',
            'date',
            'status',
            'type_id',     // Dodajemy pole type_id do tablicy danych
        ]);

        $data['user_id'] = auth()->id();

        try {
            $event = Event::create($data);

            return new JsonResponse($event->toArray(), 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'failed to save'], 500);
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

    public function update(Request $request, $id)
    {
       $event = Event::findOrFail($id);

       $request->validate([
          'type_id' => 'required|exists:types,id',
          'name' => 'required',
          'date' => 'required|date',
          'status' => 'nullable',
       ]);

       $data = $request->only([
          'name',
          'date',
          'status'
       ]);

       try {
           $event->update($data);

           if($request->has('data')) {
               $eventData = $request->input('data');

               if (is_array($eventData)) {
                   $event->companies()->detach();

                   foreach ($eventData as $item) {
                       if (isset($item['type_id']) && isset($item['company_id'])) {
                           $company = Company::find($item['company_id']);
                           if ($company) {
                               $event->companies()->attach($company);
                           }
                       }
                   }
               }
           }
           return new JsonResponse($event->toArray(), 200);
       } catch (\Exception $e) {
           return new JsonResponse(['error' => 'failed to update']);
       }
    }
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        try {
            $event->companies()->detach();

            $event->delete();

            return new JsonResponse(['message' => 'Event deleted successfully'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to delete event'], 500);
        }
    }

}
