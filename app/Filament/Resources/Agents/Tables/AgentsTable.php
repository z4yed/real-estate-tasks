<?php

namespace App\Filament\Resources\Agents\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use STS\FilamentImpersonate\Actions\Impersonate;

class AgentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount([
                'contacts',
                'tasks',
                'tasks as completed_tasks_count' => fn (Builder $query) => $query->completed(),
            ]))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (User $record): ?string => $record->email),
                TextColumn::make('contacts_count')
                    ->label('Contacts')
                    ->badge()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('tasks_count')
                    ->label('Total tasks')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('completed_tasks_count')
                    ->label('Completed')
                    ->color('success')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('progress')
                    ->state(fn (User $record): string => self::progressPercent($record).'%')
                    ->badge()
                    ->color(fn (User $record): string => self::progressColor($record))
                    ->alignCenter(),
            ])
            ->defaultSort('name')
            ->recordActions([
                Impersonate::make()
                    ->redirectTo(fn (): string => filament()->getPanel('agent')->getUrl()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function progressPercent(User $record): int
    {
        $total = (int) $record->tasks_count;

        if ($total === 0) {
            return 0;
        }

        return (int) round(((int) $record->completed_tasks_count / $total) * 100);
    }

    private static function progressColor(User $record): string
    {
        return match (true) {
            self::progressPercent($record) === 100 => 'success',
            self::progressPercent($record) >= 50 => 'warning',
            default => 'gray',
        };
    }
}
