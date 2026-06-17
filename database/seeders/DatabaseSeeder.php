<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (UserRole::cases() as $role) {
            Role::findOrCreate($role->value);
        }

        User::factory()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ])
            ->assignRole(UserRole::Admin->value);

        collect(['Alice Agent', 'Bob Agent', 'Carol Agent'])
            ->each(function (string $name, int $index): void {
                User::factory()
                    ->create([
                        'name' => $name,
                        'email' => 'agent'.($index + 1).'@example.com',
                    ])
                    ->assignRole(UserRole::Agent->value);
            });
    }
}
