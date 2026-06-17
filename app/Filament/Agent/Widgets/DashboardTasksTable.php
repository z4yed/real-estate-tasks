<?php

namespace App\Filament\Agent\Widgets;

use App\Filament\Agent\Resources\Contacts\ContactResource;
use App\Filament\Agent\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class DashboardTasksTable extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    #[On('tasks-updated')]
    public function refreshTable(): void {}

    public function table(Table $table): Table
    {
        return $table
            ->heading('Open Tasks')
            ->description('Your latest pending tasks.')
            ->query(fn (): Builder => Task::query()->forAgent(auth()->id())->notCompleted())
            ->defaultSort('id', 'desc')
            ->columns(TasksTable::columns(showContact: true))
            ->filters(TasksTable::filters())
            ->recordUrl(fn (Task $record): string => ContactResource::getUrl('view', ['record' => $record->contact_id]))
            ->recordActions([
                TasksTable::markCompleteAction(),
            ])
            ->paginated([5, 10, 25])
            ->emptyStateHeading('Nothing open')
            ->emptyStateDescription('You have no pending tasks.');
    }
}
