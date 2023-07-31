<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::all();

        if (request()->expectsJson()) {
            return response()->json($gifts);
        }

        return view('home', ['presents' => $gifts]);
    }

    public function create($event_id)
    {
        $event = Event::findOrFail($event_id);

        return view('presents.create', compact('event'));
    }



    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'necessary_requirements' => 'nullable',
                'optional_requirements' => 'nullable',
                'links' => 'sometimes|string', // Zmieniamy na typ "string"
                'event_id' => 'required|exists:events,id',
            ]);

            // Dodajmy obsługę dla pola "links" jako tablicy
            if (array_key_exists('links', $validatedData)) {
                $links = explode(',', $validatedData['links']); // Dzielimy ciąg znaków na tablicę
                $validatedData['links'] = $links;
            }


            $gift = new Gift();
            $gift->title = $validatedData['title'];
            $gift->description = $validatedData['description'];
            $gift->necessary_requirements = $validatedData['necessary_requirements'];
            $gift->optional_requirements = $validatedData['optional_requirements'];

//check if links is an array and next convert it to json before insert to db
            if (array_key_exists('links', $validatedData) && is_array($validatedData['links'])) {
                $gift->links = json_encode($validatedData['links']);
            }

            $gift->user_id = Auth::id();
            $gift->event_id = $validatedData['event_id'];
            $gift->save();

            return response()->json(['message' => 'Present created successfully']);
        } catch (ValidationException $e) {
            // ... obsługa błędów walidacji ...
        } catch (\Exception $e) {
            \Log::error($e->getMessage()); // Logujemy błąd
            return response()->json(['error' => 'An error occurred while creating the present.']);
        }
    }
    public function show($id)
    {
        $gift = Gift::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($gift);
        }

        return view('presents.show', ['gift' => $gift]);
    }

    public function edit($id)
    {
        $gift = Gift::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($gift);
        }

        return view('presents.edit', ['gift' => $gift]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'necessary_requirements' => 'nullable',
            'optional_requirements' => 'nullable',
            'links' => 'sometimes|array',
            'event_id' => 'required|exists:events,id',
        ]);

        $gift = Gift::findOrFail($id);
        $gift->title = $validatedData['title'];
        $gift->description = $validatedData['description'];
        $gift->necessary_requirements = $validatedData['necessary_requirements'];
        $gift->optional_requirements = $validatedData['optional_requirements'];

        if (array_key_exists('links', $validatedData) && is_array($validatedData['links'])) {
            $gift->links = json_encode($validatedData['links']);
        }

        $gift->event_id = $validatedData['event_id'];
        $gift->save();

        return response()->json(['message' => 'Present updated successfully']);
        //return redirect()->route('home')->with('success', 'Present updated successfully!');
    }


    public function destroy($id)
    {
        $gift = Gift::findOrFail($id);
        $gift->delete();

        return response()->json(['message' => 'Present deleted successfully']);
       // return redirect()->route('home')->with('success', 'Present deleted successfully!');
    }

    public function chooseEvents()
    {
        $events = Event::all();

        return view('presents.choose_events', compact('events'));
    }
}
