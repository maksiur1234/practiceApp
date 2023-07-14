<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Company;
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
            'visit_date' => 'required|date',
        ]);

        $user = $request->user();

        $event = new Event();
        $event->event_name = 'Visit Request';
        $event->event_start = $request->visit_date;
        $event->event_end = $request->visit_date;
        $event->company_id = $request->company_id;
        $event->user_id = $user->id;
        $event->visit_date = $request->visit_date;
        $event->event_status = 'pending'; // Set the event status to 'pending'
        $event->save();

        // Associate the event with the company
        $company = Company::findOrFail($request->company_id);
        $company->events()->save($event);

        return redirect('/home')->with('success', 'Wysłano żądanie wizyty pomyślnie!');
    }
    public function acceptVisitRequest(Request $request, $eventId)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $event = Event::findOrFail($eventId);

        if ($event->company_id != $request->company_id) {
            return redirect('/home')->with('error', 'You do not have permission to do this!');
        }

        $event->event_name = "Visit Accepted";
        $event->save();

        return redirect('/home')->with('success', 'Visit request has been accepted!');
    }
    public function rejectVisitRequest(Request $request, $eventId)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $event = Event::findOrFail($eventId);

        if ($event->company_id != $request->company_id) {
            return redirect('/home')->with('error', 'You do not have permission to do this!');
        }

        // Delete the record from the database
        $event->delete();

        return redirect('/home')->with('success', 'Visit request has been rejected!');
    }

    public function showCompanyVisitRequests($companyId)
    {
        $user = Auth::user();

        $company = Company::findOrFail($companyId);
        $visitRequests = Event::where('company_id', $companyId)
            ->whereIn('event_status', ['pending', 'accepted'])
            ->get();

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
        $companies = Company::all(); // Retrieve all companies

        return view('send_visit_requests', compact('company', 'companies', 'user'));
    }


    public function confirmVisit(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // Sprawdź, czy zalogowany użytkownik jest właścicielem firmy
        if ($event->company->user_id !== Auth::id()) {
            return redirect('/home')->with('error', 'You do not have permission to do this!');
        }

        // Zmiana statusu wizyty na "potwierdzone"
        $event->event_status = 'accepted';
        $event->save();

        return redirect('/home')->with('success', 'Visit has been confirmed!');
    }

}
