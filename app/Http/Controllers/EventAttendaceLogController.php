<?php

namespace App\Http\Controllers;

use App\Models\EventAttendanceLog;
use App\Models\EventRegister;
use Illuminate\Http\Request;

class EventAttendanceLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EventAttendanceLog::with(['user', 'event'])->orderBy('scan_time', 'desc');

        if ($request->event_id) {
            $query->where('event_register_event_id', $request->event_id);
        }
        if ($request->user_id) {
            $query->where('event_register_user_id', $request->user_id);
        }

        $logs = $query->paginate(20);
        return view('admin.event_attendance_logs.index', compact('logs')); // Example view
    }

    public function create(Request $request) // UI for manual log might be rare, usually via QR scan
    {
        $registrations = EventRegister::with(['user','event'])->get(); // Or filter more appropriately
        $selectedRegistrationUserId = $request->query('user_id');
        $selectedRegistrationEventId = $request->query('event_id');
        return view('admin.event_attendance_logs.create', compact(
            'registrations',
            'selectedRegistrationUserId',
            'selectedRegistrationEventId'
        )); // Example view
    }

    public function store(Request $request) // This would likely be an API endpoint for QR scanner
    {
        $request->validate([
            'id' => 'required|integer|unique:event_attendance_logs,id', // If manually assigning ID
            'event_register_user_id' => 'required|integer',
            'event_register_event_id' => 'required|integer',
            'qr_code' => 'required|string|max:255', // Or validate based on QR content
            'scan_time' => 'nullable|date', // Often set automatically
        ]);

         // Check if EventRegister exists
        $registrationExists = EventRegister::where('user_id', $request->event_register_user_id)
                                           ->where('event_id', $request->event_register_event_id)
                                           ->exists();
        if (!$registrationExists) {
            return back()->withInput()->with('error', 'Invalid event registration for attendance log.');
        }

        $data = $request->only(['id', 'event_register_user_id', 'event_register_event_id', 'qr_code']);
        $data['scan_time'] = $request->scan_time ?? now();

        EventAttendanceLog::create($data);

        // If API, return JSON response. If form, redirect.
        return redirect()->route('event-attendance-logs.index')->with('success', 'Attendance logged.');
    }

    public function show(EventAttendanceLog $eventAttendanceLog)
    {
        $eventAttendanceLog->load(['user', 'event']);
        return view('admin.event_attendance_logs.show', compact('eventAttendanceLog')); // Example view
    }


    public function destroy(EventAttendanceLog $eventAttendanceLog)
    {
        $eventAttendanceLog->delete();
        return redirect()->route('event-attendance-logs.index')->with('success', 'Attendance log deleted.');
    }
}