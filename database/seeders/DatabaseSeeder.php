<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $owner = User::create([
            'name'              => 'marouane radi',
            'email'             => 'marouaneradi05@taskflow.demo',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $admin = User::create([
            'name'              => 'hamza kousra',
            'email'             => 'hamzakousra@taskflow.demo',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $member1 = User::create([
            'name'              => 'elmaatouqi mohamed',
            'email'             => 'elmaatouqimohamed@taskflow.demo',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $member2 = User::create([
            'name'              => 'nceri mohamed',
            'email'             => 'ncerimohamed@taskflow.demo',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create teams
        $team1 = Team::create([
            'name'        => 'Product Team',
            'description' => 'Building the next generation product features',
            'owner_id'    => $owner->id,
        ]);

        $team2 = Team::create([
            'name'        => 'Marketing',
            'description' => 'Growth and marketing initiatives',
            'owner_id'    => $admin->id,
        ]);

        // Attach members to team1
        $team1->members()->attach($owner->id, ['role' => 'owner', 'joined_at' => now()]);
        $team1->members()->attach($admin->id, ['role' => 'admin', 'joined_at' => now()]);
        $team1->members()->attach($member1->id, ['role' => 'member', 'joined_at' => now()]);
        $team1->members()->attach($member2->id, ['role' => 'member', 'joined_at' => now()]);

        // Attach members to team2
        $team2->members()->attach($admin->id, ['role' => 'owner', 'joined_at' => now()]);
        $team2->members()->attach($owner->id, ['role' => 'member', 'joined_at' => now()]);

        // Create tasks for team1
        $tasksData = [
            ['title' => 'Design new dashboard UI', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $admin->id, 'due_date' => now()->subDays(2)],
            ['title' => 'Implement user authentication', 'status' => 'done', 'priority' => 'high', 'assigned_to' => $owner->id, 'due_date' => now()->subDays(5)],
            ['title' => 'Set up CI/CD pipeline', 'status' => 'in_progress', 'priority' => 'medium', 'assigned_to' => $member1->id, 'due_date' => now()->addDays(2)],
            ['title' => 'Write API documentation', 'status' => 'in_progress', 'priority' => 'medium', 'assigned_to' => $member2->id, 'due_date' => now()->addDays(3)],
            ['title' => 'Performance optimization', 'status' => 'todo', 'priority' => 'low', 'assigned_to' => $member1->id, 'due_date' => now()->addDays(7)],
            ['title' => 'Mobile responsiveness fixes', 'status' => 'todo', 'priority' => 'high', 'assigned_to' => $admin->id, 'due_date' => now()->addDays(1)],
            ['title' => 'Database indexing review', 'status' => 'todo', 'priority' => 'medium', 'assigned_to' => $owner->id, 'due_date' => now()->addDays(5)],
            ['title' => 'Security audit', 'status' => 'todo', 'priority' => 'high', 'assigned_to' => null, 'due_date' => now()->addDays(10)],
            ['title' => 'Refactor task service layer', 'status' => 'in_progress', 'priority' => 'medium', 'assigned_to' => $owner->id, 'due_date' => now()->addDays(4)],
            ['title' => 'User onboarding flow', 'status' => 'todo', 'priority' => 'high', 'assigned_to' => $admin->id, 'due_date' => now()->subDays(1)],
        ];

        foreach ($tasksData as $data) {
            $task = Task::create(array_merge($data, [
                'team_id'      => $team1->id,
                'created_by'   => $owner->id,
                'description'  => 'This is a sample task description for demonstration purposes.',
                'completed_at' => $data['status'] === 'done' ? now()->subDays(rand(1, 3)) : null,
            ]));

            // Log activity
            ActivityLog::create([
                'team_id'     => $team1->id,
                'user_id'     => $owner->id,
                'task_id'     => $task->id,
                'action'      => 'task_created',
                'description' => "{$owner->name} created task \"{$task->title}\"",
                'properties'  => ['title' => $task->title, 'priority' => $task->priority],
            ]);
        }

        // Some activity logs for team 1
        $activities = [
            ['user_id' => $admin->id, 'action' => 'status_changed', 'description' => 'hamza kousra changed status of "Design new dashboard UI" from in_progress to done'],
            ['user_id' => $member1->id, 'action' => 'task_assigned', 'description' => 'maatouqi mohamed was assigned "Set up CI/CD pipeline"'],
            ['user_id' => $owner->id, 'action' => 'member_added', 'description' => 'naceri mohamed added Priya Patel as member'],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create(array_merge($activity, [
                'team_id'     => $team1->id,
                'description' => $activity['description'],
            ]));
        }

        // Tasks for team2
        $team2Tasks = [
            ['title' => 'Q1 Marketing Campaign', 'status' => 'in_progress', 'priority' => 'high', 'assigned_to' => $admin->id, 'due_date' => now()->addDays(14)],
            ['title' => 'Social media strategy', 'status' => 'todo', 'priority' => 'medium', 'assigned_to' => $owner->id, 'due_date' => now()->addDays(7)],
            ['title' => 'Blog content calendar', 'status' => 'done', 'priority' => 'low', 'assigned_to' => $admin->id, 'due_date' => now()->subDays(3)],
        ];

        foreach ($team2Tasks as $data) {
            Task::create(array_merge($data, [
                'team_id'     => $team2->id,
                'created_by'  => $admin->id,
                'description' => 'Marketing task description for demonstration.',
                'completed_at' => $data['status'] === 'done' ? now()->subDays(1) : null,
            ]));
        }

        
    }
}
