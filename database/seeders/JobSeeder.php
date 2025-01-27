<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load job listings from file
        $jobListings = include database_path('seeders/data/job_listings.php');

        // Get test user id
        $testUserId = User::where('email', 'test@test.com')->first()->id;

        // Get all other user ids
        $userIds = User::where('email', '!=', 'test@test.com')->pluck('id')->toArray();

        foreach($jobListings as $index => &$listing) {

            // Assign the first 2 job listings to the test user
            if ($index < 2) {
                $listing['user_id'] = $testUserId;
            } else {
                // Assign user id to listing
                $listing['user_id'] = $userIds[array_rand($userIds)];
            }
            
            // Add timestamps
            $listing['created_at'] = now();
            $listing['updated_at'] = now();
        }

        // Insert job listings into database
        DB::table('job_listings')->insert($jobListings);
        echo "Job listings seeded successfully.\n";
    }
}