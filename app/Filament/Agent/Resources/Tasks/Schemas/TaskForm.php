<?php

namespace App\Filament\Agent\Resources\Tasks\Schemas;

use App\Enums\TaskStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class TaskForm
{
    public static function configure(Schema $schema, bool $showContact = true): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('contact_id')
                            ->label('Contact')
                            ->relationship(
                                'contact',
                                'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('agent_id', auth()->id()),
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible($showContact)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        DatePicker::make('due_date')
                            ->required()
                            ->native(false)
                            ->prefixIcon(Heroicon::OutlinedCalendar)
                            ->default(today()),
                        TimePicker::make('due_time')
                            ->seconds(false)
                            ->prefixIcon(Heroicon::OutlinedClock)
                            ->helperText('Optional'),
                        Select::make('status')
                            ->options(TaskStatus::class)
                            ->default(TaskStatus::Pending)
                            ->selectablePlaceholder(false)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
