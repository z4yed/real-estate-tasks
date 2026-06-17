<?php

namespace App\Filament\Resources\Agents\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Agents\AgentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAgent extends CreateRecord
{
    protected static string $resource = AgentResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function afterCreate(): void
    {
        /** @var User $agent */
        $agent = $this->getRecord();
        $agent->assignRole(UserRole::Agent->value);
    }
}
