<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Contact;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach (UserRole::cases() as $role) {
            Role::findOrCreate($role->value);
        }

        $this->createUser('Admin', 'admin@example.com', UserRole::Admin);

        foreach (['Alice Agent', 'Bob Agent'] as $index => $name) {
            $agent = $this->createUser($name, 'agent'.($index + 1).'@example.com', UserRole::Agent);
            $this->seedPortfolio($agent);
        }
    }

    private function createUser(string $name, string $email, UserRole $role): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make('password')],
        );

        $user->syncRoles($role->value);

        return $user;
    }

    private function seedPortfolio(User $agent): void
    {
        $firstName = explode(' ', $agent->name)[0];

        $contacts = [
            [
                'name' => "{$firstName}'s Buyer - Olivia Reed",
                'email' => 'olivia.reed@example.com',
                'phone' => '+1 (555) 100-2000',
                'tasks' => [
                    ['Call to confirm viewing time', -3, TaskStatus::Pending],
                    ['Send updated listing brochure', 0, TaskStatus::InProgress],
                ],
            ],
            [
                'name' => "{$firstName}'s Seller - Daniel Cruz",
                'email' => 'daniel.cruz@example.com',
                'phone' => '+1 (555) 200-3000',
                'tasks' => [
                    ['Schedule professional photography', 2, TaskStatus::Pending],
                    ['Review staging proposal', -6, TaskStatus::Completed],
                ],
            ],
        ];

        foreach ($contacts as $data) {
            $contact = Contact::firstOrCreate(
                ['agent_id' => $agent->id, 'name' => $data['name']],
                ['email' => $data['email'], 'phone' => $data['phone']],
            );

            foreach ($data['tasks'] as [$description, $dueOffset, $status]) {
                Task::firstOrCreate(
                    ['contact_id' => $contact->id, 'description' => $description],
                    [
                        'due_date' => today()->addDays($dueOffset),
                        'status' => $status,
                        'completed_at' => $status === TaskStatus::Completed
                            ? now()->addDays(min($dueOffset, 0))
                            : null,
                    ],
                );
            }
        }
    }
}
