<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Resources\Organizations\Schemas\OrganizationRegisterForm;
use App\Models\Organization;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class RegisterOrganization extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Organization';
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::FourExtraLarge;
    }


    public function form(Schema $schema): Schema
    {
        return OrganizationRegisterForm::configure($schema);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create($data);

        $organization->users()->attach(auth()->user());

        return $organization;
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
