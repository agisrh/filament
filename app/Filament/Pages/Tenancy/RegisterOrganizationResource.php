<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Resources\Organizations\Schemas\OrganizationForm;
use App\Models\Organization;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterOrganizationResource extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register organization';
    }

    public function form(Schema $schema): Schema
    {
        return OrganizationForm::configure($schema);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create($data);

        $organization->users()->attach(auth()->user());

        return $organization;
    }
}