<?php

namespace App\Filament\Agent\Resources\Contacts\Schemas;

use App\Livewire\ContactTasksTable;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Contact Details')
                            ->icon(Heroicon::OutlinedUser)
                            ->schema([
                                Section::make()
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Full name')
                                            ->weight('bold')
                                            ->columnSpanFull(),
                                        TextEntry::make('email')
                                            ->icon(Heroicon::OutlinedEnvelope)
                                            ->copyable()
                                            ->placeholder('—'),
                                        TextEntry::make('phone')
                                            ->icon(Heroicon::OutlinedPhone)
                                            ->copyable()
                                            ->placeholder('—'),
                                        TextEntry::make('created_at')
                                            ->label('Added')
                                            ->since()
                                            ->icon(Heroicon::OutlinedCalendar),
                                    ]),
                                Section::make('Notes')
                                    ->icon(Heroicon::OutlinedPencilSquare)
                                    ->collapsible()
                                    ->schema([
                                        TextEntry::make('notes')
                                            ->hiddenLabel()
                                            ->placeholder('No notes yet.')
                                            ->prose(),
                                    ]),
                            ]),
                        Tab::make('Tasks')
                            ->icon(Heroicon::OutlinedClipboardDocumentList)
                            ->badge(fn ($record): int => $record->tasks()->count())
                            ->schema([
                                Livewire::make(ContactTasksTable::class)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
