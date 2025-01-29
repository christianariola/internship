<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Job;

class JobController extends Controller
{

    use AuthorizesRequests;

    // @desc   Show all job listings
    // @route  GET /jobs
    public function index(): View
    {
        $jobs = Job::paginate(9);

        return view('jobs.index')->with('jobs', $jobs);
    }

    // @desc   Show create job forn
    // @route  GET /jobs/create
    public function create(): View
    {
        return view('jobs.create');
    }

    // @desc   Save job to database
    // @route  POST /jobs
    public function store(Request $request): RedirectResponse
    {
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_website' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Assogm user_id to job listing
        $validateData['user_id'] = Auth::user()->id;

        // Check for image
        if ($request->hasFile('company_logo')) {
            // Store the file and get the path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add the path to the validateData array
            $validateData['company_logo'] = $path;
        }

        Job::create($validateData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully.');
    }

    // @desc   Display a single job listing
    // @route  GET /jobs/{$id}
    // Job $job is a Route Model Binding
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    // @desc   Show edit job form
    // @route  GET /jobs/{$id}/edit
    public function edit(Job $job): View
    {
        // Check if the user is authorized to update the job listing
        $this->authorize('update', $job);

        return view('jobs.edit')->with('job', $job);
    }

    // @desc   Show all job listings
    // @route  PUT /jobs/{$id}/
    public function update(Request $request, Job $job): RedirectResponse
    {

        // Check if the user is authorized to update the job listing
        $this->authorize('update', $job);

        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_website' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Check for image
        if ($request->hasFile('company_logo')) {
            // Delete the old image
            Storage::delete('public/logos/' . basename($job->company_logo));

            // Store the file and get the path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add the path to the validateData array
            $validateData['company_logo'] = $path;
        }

        $job->update($validateData);

        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully.');
    }

    // @desc   Delete a job listing
    // @route  DELETE /jobs/{$id}
    public function destroy(Job $job): RedirectResponse
    {

        // Check if the user is authorized to delete the job listing
        $this->authorize('delete', $job);

        // If logo exists, delete it
        if ($job->company_logo) {
            Storage::delete('public/logos/' . basename($job->company_logo));
        }

        $job->delete();

        // Check if request came from dashboard
        if(request()->query('from') == 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully.');
        }

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully.');
    }
    
}