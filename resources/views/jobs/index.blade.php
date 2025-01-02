<x-layout>
    <h1 class="text-2xl">All Jobs</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @forelse($jobs as $job)
            <div>{{ $job->title }}</div>
        @empty
            <p>No jobs available</p>
        @endforelse
    </div>
</x-layout>