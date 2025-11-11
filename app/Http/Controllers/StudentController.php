<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by month
        if ($request->has('month') && !empty($request->month)) {
            $query->whereMonth('registration_date', $request->month);
        }
        
        $students = $query->latest()->paginate(10);
        
        return view('students.index', compact('students'));
    }
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'registration_date' => 'required|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Handle file uploads
        $profilePhotoPath = null;
        $birthCertificatePath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->hasFile('birth_certificate')) {
            $birthCertificatePath = $request->file('birth_certificate')->store('birth_certificates', 'public');
        }

        // Create the student
        $student = Student::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'],
            'registration_date' => $validated['registration_date'],
            'profile_photo' => $profilePhotoPath,
            'birth_certificate' => $birthCertificatePath,
        ]);

        return redirect()->route('students.index')->with('success', 'تم إنشاء الطالب بنجاح !');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'registration_date' => 'required|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        // Handle file uploads
        $profilePhotoPath = $student->profile_photo;
        $birthCertificatePath = $student->birth_certificate;

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->hasFile('birth_certificate')) {
            // Delete old certificate if exists
            if ($student->birth_certificate) {
                Storage::disk('public')->delete($student->birth_certificate);
            }
            $birthCertificatePath = $request->file('birth_certificate')->store('birth_certificates', 'public');
        }

        // Update the student
        $student->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'],
            'registration_date' => $validated['registration_date'],
            'profile_photo' => $profilePhotoPath,
            'birth_certificate' => $birthCertificatePath,
        ]);

        return redirect()->route('students.index')->with('success', 'تم تحديث الطالب بنجاح !');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Delete associated files
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }
        
        if ($student->birth_certificate) {
            Storage::disk('public')->delete($student->birth_certificate);
        }
        
        $student->delete();
        
        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}
