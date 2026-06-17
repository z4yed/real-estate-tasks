<?php

namespace App\Filament\Agent\Widgets;

use App\Filament\Agent\Resources\Tasks\TaskResource;
use App\Models\Task;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class TaskStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Outstanding';

    protected ?string $description = 'Open tasks needing your attention.';

    protected int|array|null $columns = 4;

    #[On('tasks-updated')]
    public function refreshStats(): void {}

    protected function getStats(): array
    {
        $base = fn (): Builder => Task::query()->forAgent(auth()->id());

        return [
            Stat::make('Overdue', $base()->overdue()->count())
                ->description('Past due & still open')
                ->descriptionIcon(Heroicon::OutlinedExclamationTriangle)
                ->color('danger')
                ->url(TaskResource::getUrl('index', ['tab' => 'overdue'])),
            Stat::make('Due Today', $base()->dueToday()->notCompleted()->count())
                ->description('Open & scheduled for today')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('warning')
                ->url(TaskResource::getUrl('index', ['tab' => 'today'])),
            Stat::make('Due This Week', $base()->dueThisWeek()->notCompleted()->count())
                ->description('Open & due this week')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('info')
                ->url(TaskResource::getUrl('index', ['tab' => 'this_week'])),
            Stat::make('Due This Month', $base()->dueThisMonth()->notCompleted()->count())
                ->description('Open & due this month')
                ->descriptionIcon(Heroicon::OutlinedCalendar)
                ->color('info')
                ->url(TaskResource::getUrl('index', ['tab' => 'this_month'])),
        ];
    }
}
