<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Restrict each Filament panel to users holding the matching role.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->hasRole(UserRole::Admin->value),
            'agent' => $this->hasRole(UserRole::Agent->value),
            default => false,
        };
    }

    public function canImpersonate(): bool
    {
        return $this->hasRole(UserRole::Admin->value);
    }

    public function canBeImpersonated(): bool
    {
        return $this->hasRole(UserRole::Agent->value);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'agent_id');
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Contact::class, 'agent_id', 'contact_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
