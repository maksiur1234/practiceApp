<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gift;
use Illuminate\Support\Facades\Auth;

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

    public function create()
    {
        return view('presents.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'necessary_requirements' => 'nullable',
            'optional_requirements' => 'nullable',
            'links' => 'sometimes|array',
        ]);

        $gift = new Gift();
        $gift->title = $validatedData['title'];
        $gift->description = $validatedData['description'];
        $gift->necessary_requirements = $validatedData['necessary_requirements'];
        $gift->optional_requirements = $validatedData['optional_requirements'];

        if (array_key_exists('links', $validatedData)) {
            $gift->links = $validatedData['links'];
        }

        $gift->user_id = Auth::id();

        $gift->save();

        return response()->json(['message' => 'Present created successfully']);
        //return redirect()->route('home')->with('success', 'Present created successfully!');

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
            'links' => 'sometimes|array'
        ]);

        $gift = Gift::findOrFail($id);
        $gift->title = $validatedData['title'];
        $gift->description = $validatedData['description'];
        $gift->necessary_requirements = $validatedData['necessary_requirements'];
        $gift->optional_requirements = $validatedData['optional_requirements'];

        // Check if 'links' key exists in $validatedData before assigning
        if (array_key_exists('links', $validatedData)) {
            $gift->links = $validatedData['links'];
        }

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
}
