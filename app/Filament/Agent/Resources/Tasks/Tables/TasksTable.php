<?php

namespace App\Filament\Agent\Resources\Tasks\Tables;

use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::columns())
            ->defaultSort('due_date', 'asc')
            ->filters(self::filters())
            ->recordActions([
                self::startAction(),
                self::markCompleteAction(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No tasks yet')
            ->emptyStateIcon(Heroicon::OutlinedClipboardDocumentList);
    }

    /**
     * @return array<Column>
     */
    public static function columns(bool $showContact = true): array
    {
        return array_values(array_filter([
            $showContact
                ? TextColumn::make('contact.name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                : null,
            TextColumn::make('description')
                ->wrap()
                ->limit(60)
                ->searchable(),
            TextColumn::make('due_date')
                ->label('Due')
                ->date('M j, Y')
                ->description(fn (Task $record): ?string => $record->due_time
                    ? Carbon::parse($record->due_time)->format('g:i A')
                    : null)
                ->sortable()
                ->color(fn (Task $record): string => $record->isOverdue() ? 'danger' : 'gray'),
            TextColumn::make('status')
                ->badge(),
        ]));
    }

    /**
     * @return array<BaseFilter>
     */
    public static function filters(): array
    {
        return [
            SelectFilter::make('status')
                ->options(TaskStatus::class),
            Filter::make('overdue')
                ->label('Overdue only')
                ->query(fn (Builder $query) => $query->overdue()),
        ];
    }

    public static function startAction(): Action
    {
        return Action::make('start')
            ->label('Mark in progress')
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('warning')
            ->visible(fn (Task $record): bool => $record->status === TaskStatus::Pending)
            ->action(function (Task $record): void {
                $record->update(['status' => TaskStatus::InProgress]);

                Notification::make()
                    ->title('Task marked in progress')
                    ->success()
                    ->send();
            });
    }

    public static function markCompleteAction(): Action
    {
        return Action::make('complete')
            ->label('Mark complete')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(fn (Task $record): bool => $record->status === TaskStatus::InProgress)
            ->action(function (Task $record): void {
                $record->update(['status' => TaskStatus::Completed]);

                Notification::make()
                    ->title('Task completed')
                    ->success()
                    ->send();
            });
    }
}
