<?php

namespace App\Filament\Resources\UserCustomers\Pages;

use App\Filament\Resources\UserCustomers\UserCustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUserCustomer extends EditRecord
{
    protected static string $resource = UserCustomerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
