<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create skills
        $skills = [
            ['name' => 'Car Washing', 'category' => 'Service'],
            ['name' => 'Interior Cleaning', 'category' => 'Service'],
            ['name' => 'Polishing', 'category' => 'Service'],
            ['name' => 'Detailing', 'category' => 'Service'],
            ['name' => 'Customer Service', 'category' => 'Soft Skills'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Create test users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@carshine.com',
                'employee_id' => 'EMP001',
                'password' => Hash::make('password'),
                'position' => 'Senior Staff',
                'department' => 'Service',
                'status' => 'active',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@carshine.com',
                'employee_id' => 'EMP002',
                'password' => Hash::make('password'),
                'position' => 'Staff',
                'department' => 'Service',
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Assign random skills
            $user->skills()->attach(
                Skill::inRandomOrder()->take(3)->pluck('id'),
                ['proficiency_level' => rand(3, 5)]
            );
        }

        // Create some test tasks
        $tasks = [
            [
                'title' => 'Full Car Wash - Toyota Camry',
                'description' => 'Complete exterior and interior cleaning',
                'assigned_to' => 1,
                'assigned_by' => 1,
                'due_date' => now()->addHours(2),
                'priority' => 'high',
                'status' => 'pending',
                'weight' => 3,
                'required_skill_id' => 1,
                'workload_units' => 20,
            ],
            [
                'title' => 'Interior Detailing - Honda Civic',
                'description' => 'Deep clean interior including seats and carpets',
                'assigned_to' => 2,
                'assigned_by' => 1,
                'due_date' => now()->addHours(4),
                'priority' => 'medium',
                'status' => 'pending',
                'weight' => 2,
                'required_skill_id' => 2,
                'workload_units' => 15,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}