<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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

        foreach (['Alice Agent', 'Bob Agent', 'Carol Agent'] as $index => $name) {
            $this->createUser($name, 'agent'.($index + 1).'@example.com', UserRole::Agent);
        }
    }

    private function createUser(string $name, string $email, UserRole $role): void
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make('password')],
        );

        $user->syncRoles($role->value);
    }
}
