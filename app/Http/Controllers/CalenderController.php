<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

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
        $event->save();

        return response()->json(['message' => 'Request sent successfully']);
    }

    public function acceptVisitRequest(Request $request, $eventId)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $event = Event::findOrFail($eventId);

        if ($event->company_id != $request->company_id) {
            return response()->json(['message' => 'You do not have permission for that!'], 403);
        }

        $event->event_name = "Visit Accepted";
        $event->save();

        return response()->json(['message' => 'Visit accepted']);
    }

    public function rejectVisitRequest(Request $request, $eventId)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $event = Event::findOrFail($eventId);

        if($event->company_id != $request->company_id) {
            return response()->json(['message' => 'You do not have permission for that!'], 403);
        }

        $event->event_name = "Visit Rejected"; // Poprawka: zmieniono na "Visit Rejected"
        $event->save();

        return response()->json(['message' => 'Visit rejected']);
    }

}
