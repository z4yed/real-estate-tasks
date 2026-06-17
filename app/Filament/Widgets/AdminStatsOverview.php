<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Contact;
use App\Models\Task;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Overview';

    protected function getStats(): array
    {
        $totalTasks = Task::query()->count();
        $completedTasks = Task::query()->completed()->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return [
            Stat::make('Agents', User::query()->role(UserRole::Agent->value)->count())
                ->description('Active agents')
                ->descriptionIcon(Heroicon::OutlinedUserGroup)
                ->color('info'),
            Stat::make('Contacts', Contact::query()->count())
                ->description('Across all agents')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('info'),
            Stat::make('Tasks', $totalTasks)
                ->description('All tasks created')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentList)
                ->color('warning'),
            Stat::make('Completed', $completedTasks)
                ->description("{$completionRate}% completion rate")
                ->descriptionIcon(Heroicon::OutlinedCheckBadge)
                ->color('success'),
        ];
    }
}
