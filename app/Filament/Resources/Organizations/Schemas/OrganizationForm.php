<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('logo'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('website')
                    ->url(),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('region_code'),
                TextInput::make('status')
                    ->default('active'),
            ]);
    }
}
