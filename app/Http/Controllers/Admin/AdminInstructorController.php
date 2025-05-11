<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInstructorController extends Controller
{
    public function create()
    {
        return view('admin.instructors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'experience' => 'required|string|max:500',
            'about' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('instructors', 'public');
            $validated['photo'] = $path;
        }

        Instructor::create($validated);

        return redirect()->route('admin.instructors.create')->with('success', 'Инструктор успешно создан!');
    }
}
