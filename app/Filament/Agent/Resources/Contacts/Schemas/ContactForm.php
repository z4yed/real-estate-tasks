<?php

namespace App\Filament\Agent\Resources\Contacts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Details')
                    ->description('Who is this client and how do you reach them?')
                    ->icon(Heroicon::OutlinedUser)
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->prefixIcon(Heroicon::OutlinedEnvelope),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->prefixIcon(Heroicon::OutlinedPhone),
                    ]),

                Section::make('Notes')
                    ->description('Background, preferences, or anything worth remembering.')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->collapsible()
                    ->schema([
                        Textarea::make('notes')
                            ->hiddenLabel()
                            ->rows(5)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
