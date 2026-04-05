<?php

namespace App\Http\Controllers;

use App\Models\EventRegister;
use App\Models\Event;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // For file uploads

class EventRegisterController extends Controller
{
    public function index(Request $request)
    {
        // Example: List registrations for a specific event, or all
        $query = EventRegister::with(['user', 'event', 'status']);

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        // Add other filters as needed

        $registrations = $query->paginate(15);
        return view('admin.event_registers.index', compact('registrations')); // Example view
    }

    public function create(Request $request) // Optionally pass event_id if creating for a specific event
    {
        $users = User::orderBy('name')->get();
        $events = Event::orderBy('title')->get();
        $statuses = Status::orderBy('name')->get();
        $selectedEventId = $request->query('event_id'); // Get event_id from query string

        return view('admin.event_registers.create', compact('users', 'events', 'statuses', 'selectedEventId')); // Example view
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'event_id' => 'required|integer|exists:events,id',
            'status_id' => 'required|integer|exists:status,id',
            'payment_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation
            // Ensure unique combination of user_id and event_id
            // This validation is a bit tricky with forms.
            // Rule::unique('event_register')->where(function ($query) use ($request) {
            //     return $query->where('user_id', $request->user_id)
            //                  ->where('event_id', $request->event_id);
            // }),
        ]);

        // Check for existing registration manually due to composite key
        $existing = EventRegister::where('user_id', $request->user_id)
                                 ->where('event_id', $request->event_id)
                                 ->first();
        if ($existing) {
            return back()->withInput()->with('error', 'This user is already registered for this event.');
        }

        $data = $request->only(['user_id', 'event_id', 'status_id']);

        if ($request->hasFile('payment_file')) {
            $path = $request->file('payment_file')->store('payment_proofs', 'public'); // Example storage
            $data['payment_file'] = $path;
        }

        EventRegister::create($data);

        return redirect()->route('event-registers.index', ['event_id' => $request->event_id])
                         ->with('success', 'Event registration successful.');
    }

    // Display function for composite keys would require fetching by both keys
    public function show($userId, $eventId) // Not standard resource route, adjust routes if needed
    {
        $registration = EventRegister::where('user_id', $userId)
                                     ->where('event_id', $eventId)
                                     ->with(['user', 'event', 'status', 'certificates', 'eventAttendanceLogs', 'files'])
                                     ->firstOrFail();
        return view('admin.event_registers.show', compact('registration')); // Example view
    }

    // Edit function for composite keys
    public function edit($userId, $eventId) // Not standard resource route
    {
        $registration = EventRegister::where('user_id', $userId)
                                     ->where('event_id', $eventId)
                                     ->firstOrFail();
        $users = User::orderBy('name')->get();
        $events = Event::orderBy('title')->get();
        $statuses = Status::orderBy('name')->get();
        return view('admin.event_registers.edit', compact('registration', 'users', 'events', 'statuses')); // Example view
    }

    public function update(Request $request, $userId, $eventId) // Not standard resource route
    {
        $registration = EventRegister::where('user_id', $userId)
                                     ->where('event_id', $eventId)
                                     ->firstOrFail();
        $request->validate([
            // user_id and event_id usually shouldn't change for an existing registration
            'status_id' => 'required|integer|exists:status,id',
            'payment_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only(['status_id']);

        if ($request->hasFile('payment_file')) {
            // Delete old file if it exists
            if ($registration->payment_file) {
                Storage::disk('public')->delete($registration->payment_file);
            }
            $path = $request->file('payment_file')->store('payment_proofs', 'public');
            $data['payment_file'] = $path;
        } elseif ($request->boolean('remove_payment_file')) { // Add a checkbox in form to remove
             if ($registration->payment_file) {
                Storage::disk('public')->delete($registration->payment_file);
            }
            $data['payment_file'] = null;
        }

        $registration->update($data);

        return redirect()->route('event-registers.index', ['event_id' => $registration->event_id])
                         ->with('success', 'Registration updated successfully.');
    }

    public function destroy($userId, $eventId) // Not standard resource route
    {
        $registration = EventRegister::where('user_id', $userId)
                                     ->where('event_id', $eventId)
                                     ->firstOrFail();
        // Handle file deletion
        if ($registration->payment_file) {
            Storage::disk('public')->delete($registration->payment_file);
        }
        // Cascade delete should handle certificates etc. if DB constraints are set.
        $registration->delete();
        return redirect()->route('event-registers.index')->with('success', 'Registration deleted successfully.');
    }
}