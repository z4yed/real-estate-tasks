<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'contact_id',
        'description',
        'due_date',
        'due_time',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'status' => TaskStatus::class,
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Task $task): void {
            $task->completed_at = $task->status === TaskStatus::Completed
                ? ($task->completed_at ?? now())
                : null;
        });
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === TaskStatus::Completed;
    }

    public function isOverdue(): bool
    {
        return ! $this->isCompleted() && $this->due_date?->isBefore(today());
    }

    public function scopeForAgent(Builder $query, int $agentId): Builder
    {
        return $query->whereHas('contact', fn (Builder $q) => $q->where('agent_id', $agentId));
    }

    public function scopeNotCompleted(Builder $query): Builder
    {
        return $query->where('status', '!=', TaskStatus::Completed->value);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Completed->value);
    }

    public function scopeCompletedToday(Builder $query): Builder
    {
        return $query->completed()->whereDate('completed_at', today());
    }

    public function scopeCompletedThisWeek(Builder $query): Builder
    {
        return $query->completed()->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeCompletedThisMonth(Builder $query): Builder
    {
        return $query->completed()->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->notCompleted()->whereDate('due_date', '<', today());
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeDueThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeDueThisMonth(Builder $query): Builder
    {
        return $query->whereBetween('due_date', [now()->startOfMonth(), now()->endOfMonth()]);
    }
}
