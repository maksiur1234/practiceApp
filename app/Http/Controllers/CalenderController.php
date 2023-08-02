<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Company;
use App\Models\Type;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class CalenderController extends Controller
{
    public function sendVisitRequest(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'date' => 'required|date',
        ]);

        $user = $request->user();

        $event = new Event();
        $event->name = 'Visit Request';
        $event->status = 'pending';
        $event->date = $request->visit_date;
        $event->company_id = $request->company_id;
        $event->user_id = $user->id;

        $event->save();

        $company = Company::findOrFail($request->company_id);
        $company->events()->save($event);

        return response()->json(['message' => 'Visit request sent successfully']);
    }

    public function acceptVisitRequest(Request $request, $eventId)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $event = Event::findOrFail($eventId);

        if ($event->company_id != $request->company_id) {
            return response()->json(['error' => 'You do not have permission to do this!'], 403);
        }

        $event->name = "Visit Accepted";
        $event->save();

        return response()->json(['message' => 'Visit request has been accepted']);
    }

    public function rejectVisitRequest(Request $request, $eventId)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $event = Event::findOrFail($eventId);

        if ($event->company_id != $request->company_id) {
            return response()->json(['error' => 'You do not have permission to do this!'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Visit request has been rejected']);
    }

    public function showCompanyVisitRequests($companyId)
    {
        $user = Auth::user();

        $company = Company::findOrFail($companyId);
        $visitRequests = Event::where('company_id', $companyId)->get();

        return view('company_visit_requests', compact('company', 'visitRequests', 'user'));
    }


    public function showVisitRequestDetails($eventId)
    {
        $event = Event::findOrFail($eventId);

        return view('home', compact('event'));
    }

    public function showVisitRequestForm($companyId)
    {
        $user = Auth::user();

        $company = Company::findOrFail($companyId);
        $companies = Company::all();

        return view('send_visit_requests', compact('company', 'companies', 'user'));
    }


    public function confirmVisit(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($event->company->user_id !== Auth::id()) {
            return redirect('/home')->with('error', 'You do not have permission to do this!');
        }

        $event->status = 'accepted';
        $event->save();

        return redirect('/home')->with('success', 'Visit has been confirmed!');
    }

}
