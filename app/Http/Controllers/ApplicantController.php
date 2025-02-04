<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobApplied;

class ApplicantController extends Controller
{
    // @desc Store applicant to database
    // @route POST /jobs/{job}/apply

    public function store(Request $request, Job $job)
    {

        // Check if user has already applied for the job
        $existingApplication = Applicant::where('job_id', $job->id)
            ->where('user_id', Auth::user()->id)
            ->exists();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job');
        }

        // Validate incoming data
        $validatedData = $request->validate([
            'full_name' => 'required|string',
            'contact_phone' => 'string',
            'contact_email' => 'required|string|email',
            'message' => 'string',
            'location' => 'string',
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Handle resume uplaod
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume_path'] = $path;
        }


        // Store the application
        $application = new Applicant($validatedData);

        $application->job_id = $job->id;
        $application->user_id = Auth::user()->id;
        // dd($application);
        $application->save();

        // Send email to job poster
        Mail::to($job->user->email)->send(new JobApplied($application, $job));
        
        return redirect()->back()->with('success', 'Your application has been submitted');
    }

    // @desc    Delete job applicant
    // @route   DELETE /applicants/{applicant}
    public function destroy($id): RedirectResponse
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();
        return redirect()->route('dashboard')->with('success', 'Applicant deleted successfully!');
    }
}