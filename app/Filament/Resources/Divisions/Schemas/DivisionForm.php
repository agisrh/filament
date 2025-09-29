<?php

namespace App\Filament\Resources\Divisions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DivisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
