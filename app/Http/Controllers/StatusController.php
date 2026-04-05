<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('name')->get();
        return view('admin.statuses.index', compact('statuses')); // Example view
    }

    public function create()
    {
        return view('admin.statuses.create'); // Example view
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|unique:status,id', // If manually assigning ID
            'name' => 'required|string|max:255|unique:status,name',
        ]);

        Status::create($request->all());
        return redirect()->route('statuses.index')->with('success', 'Status created.');
    }

    public function edit(Status $status)
    {
        return view('admin.statuses.edit', compact('status')); // Example view
    }

    public function update(Request $request, Status $status)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:status,name,' . $status->id,
        ]);
        $status->update($request->only('name'));
        return redirect()->route('statuses.index')->with('success', 'Status updated.');
    }

    public function destroy(Status $status)
    {
        // Consider checking if status is in use by event_registrations
        $status->delete();
        return redirect()->route('statuses.index')->with('success', 'Status deleted.');
    }
}