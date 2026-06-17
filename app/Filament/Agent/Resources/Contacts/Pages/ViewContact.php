<?php

namespace App\Filament\Agent\Resources\Contacts\Pages;

use App\Filament\Agent\Resources\Contacts\ContactResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    public function getTitle(): string
    {
        return "Contact Profile: {$this->getRecord()->name}";
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
