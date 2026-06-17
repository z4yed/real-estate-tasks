<?php

namespace App\Livewire;

use App\Filament\Agent\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Agent\Resources\Tasks\Tables\TasksTable;
use App\Models\Contact;
use App\Models\Task;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ContactTasksTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public Contact $contact;

    public function mount(Contact $record): void
    {
        $this->contact = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Task::query()->where('contact_id', $this->contact->getKey()))
            ->columns(TasksTable::columns(showContact: false))
            ->defaultSort('due_date', 'asc')
            ->filters(TasksTable::filters())
            ->headerActions([
                CreateAction::make()
                    ->label('New task')
                    ->icon(Heroicon::Plus)
                    ->model(Task::class)
                    ->schema(fn (Schema $schema): Schema => TaskForm::configure($schema, showContact: false))
                    ->mutateDataUsing(function (array $data): array {
                        $data['contact_id'] = $this->contact->getKey();

                        return $data;
                    }),
            ])
            ->recordActions([
                TasksTable::startAction(),
                TasksTable::markCompleteAction(),
                EditAction::make()
                    ->schema(fn (Schema $schema): Schema => TaskForm::configure($schema, showContact: false)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.contact-tasks-table');
    }
}
