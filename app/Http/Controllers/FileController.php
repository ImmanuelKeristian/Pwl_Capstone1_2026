<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\EventRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request) // List all managed files
    {
        $query = File::query();
        if ($request->event_id) {
            $query->where('event_register_event_id', $request->event_id);
        }
        // Add other filters

        $files = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.files.index', compact('files')); // Example view
    }

    // Create/Store for general files. Often files are uploaded in context of another model.
    // For example, a payment_file is uploaded via EventRegisterController.
    // This could be for a more generic file upload area.
    public function create()
    {
        // You might pass related models if files are associated during creation
        return view('admin.files.create'); // Example view
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|unique:files,id', // If manually assigning ID
            'upload_file' => 'required|file|max:10240', // Example: max 10MB
            'file_name' => 'nullable|string|max:255', // Optional: if user provides a display name
            // Optional: fields for associating with event_register
            'event_register_user_id' => 'nullable|integer|exists:users,id',
            'event_register_event_id' => 'nullable|integer|exists:events,id',
        ]);

        $uploadedFile = $request->file('upload_file');
        $originalName = $uploadedFile->getClientOriginalName();
        $mimeType = $uploadedFile->getClientMimeType();
        // Store in a directory like 'general_uploads/YYYY/MM'
        $filePath = $uploadedFile->store('general_uploads/'.date('Y').'/'.date('m'), 'public');

        $fileData = [
            'id' => $request->id,
            'file_name' => $request->file_name ?? $originalName,
            'file_path' => $filePath,
            'file_type' => $mimeType, // Storing MIME type
            'event_register_user_id' => $request->event_register_user_id,
            'event_register_event_id' => $request->event_register_event_id,
        ];

        // If associating, ensure the EventRegister exists
        if ($request->event_register_user_id && $request->event_register_event_id) {
            $registrationExists = EventRegister::where('user_id', $request->event_register_user_id)
                                           ->where('event_id', $request->event_register_event_id)
                                           ->exists();
            if (!$registrationExists) {
                // Rollback file upload or handle error
                Storage::disk('public')->delete($filePath);
                return back()->withInput()->with('error', 'Associated Event Registration not found.');
            }
        }


        $file = File::create($fileData);

        return redirect()->route('files.index')->with('success', 'File uploaded successfully.');
    }

    public function show(File $file)
    {
        return view('admin.files.show', compact('file')); // Example view
    }

    // // Download method
    // public function download(File $file)
    // {
    //     if (!Storage::disk('public')->exists($file->file_path)) {
    //         abort(404, 'File not found.');
    //     }
    //     return Storage::disk('public')->download($file->file_path, $file->file_name);
    // }


    public function destroy(File $file)
    {
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();
        return redirect()->route('files.index')->with('success', 'File deleted successfully.');
    }
}