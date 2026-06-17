<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasLabel
{
    case Admin = 'admin';
    case Agent = 'agent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Agent => 'Agent',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Agent => 'info',
        };
    }
}
