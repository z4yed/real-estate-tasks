<?php

namespace App\Filament\Agent\Widgets;

use App\Filament\Agent\Resources\Tasks\TaskResource;
use App\Models\Task;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class CompletedTaskStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Completed';

    protected ?string $description = 'Your finished work over time.';

    protected int|array|null $columns = 4;

    #[On('tasks-updated')]
    public function refreshStats(): void {}

    protected function getStats(): array
    {
        $base = fn (): Builder => Task::query()->forAgent(auth()->id());

        return [
            Stat::make('Completed Today', $base()->completedToday()->count())
                ->description('Finished today')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Completed This Week', $base()->completedThisWeek()->count())
                ->description('Finished this week')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Completed This Month', $base()->completedThisMonth()->count())
                ->description('Finished this month')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Total Completed', $base()->completed()->count())
                ->description('All finished tasks')
                ->descriptionIcon(Heroicon::OutlinedCheckBadge)
                ->color('success')
                ->url(TaskResource::getUrl('index', ['tab' => 'completed'])),
        ];
    }
}
