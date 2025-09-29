<?php
namespace App\Filament\Pages\Tenancy;

use App\Models\Organization;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterOrganization extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register organization';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code'),
                TextInput::make('name'),
                TextInput::make('slug'),
                TextInput::make('logo'),
                TextInput::make('phone'),
                TextInput::make('email'),
                TextInput::make('website'),
                TextInput::make('address'),
                TextInput::make('status'),
            ]);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create($data);

        $organization->users()->attach(auth()->user());

        return $organization;
    }
}