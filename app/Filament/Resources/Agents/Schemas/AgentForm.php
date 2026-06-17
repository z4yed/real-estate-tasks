<?php

namespace App\Filament\Resources\Agents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class AgentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Agent Account')
                    ->description('Login details for this agent.')
                    ->icon(Heroicon::OutlinedUser)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnStart(1)
                            ->prefixIcon(Heroicon::OutlinedEnvelope),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->columnStart(1)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->helperText('Leave blank to keep the current password.')
                            ->maxLength(255),
                    ]),
            ]);
    }
}
