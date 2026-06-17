<?php

namespace App\Filament\Agent\Resources\Tasks\Pages;

use App\Filament\Agent\Resources\Tasks\TaskResource;
use App\Models\Task;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    #[On('tasks-updated')]
    public function refreshTabs(): void {}

    public function getTabs(): array
    {
        $count = fn (callable $scope): int => $scope(Task::query()->forAgent(auth()->id()))->count();

        return [
            'all' => Tab::make('All'),
            'overdue' => Tab::make('Overdue')
                ->modifyQueryUsing(fn (Builder $query) => $query->overdue())
                ->badge($count(fn (Builder $q) => $q->overdue()))
                ->badgeColor('danger'),
            'today' => Tab::make('Today')
                ->modifyQueryUsing(fn (Builder $query) => $query->dueToday()->notCompleted())
                ->badge($count(fn (Builder $q) => $q->dueToday()->notCompleted()))
                ->badgeColor('warning'),
            'this_week' => Tab::make('This Week')
                ->modifyQueryUsing(fn (Builder $query) => $query->dueThisWeek()->notCompleted())
                ->badge($count(fn (Builder $q) => $q->dueThisWeek()->notCompleted()))
                ->badgeColor('info'),
            'this_month' => Tab::make('This Month')
                ->modifyQueryUsing(fn (Builder $query) => $query->dueThisMonth()->notCompleted())
                ->badge($count(fn (Builder $q) => $q->dueThisMonth()->notCompleted()))
                ->badgeColor('info'),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->completed())
                ->badge($count(fn (Builder $q) => $q->completed()))
                ->badgeColor('success'),
        ];
    }
}
