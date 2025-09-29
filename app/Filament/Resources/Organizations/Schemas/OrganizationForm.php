<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use App\Helpers\IndonesiaRegionHelper;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;


class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Basic Information')
                        ->icon('heroicon-m-building-office')
                        ->description('Enter basic organization details')
                        ->schema([
                            TextInput::make('code')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(50),
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            FileUpload::make('logo')
                                ->image()
                                ->maxSize(2048)
                                ->directory('organization-logos'),
                        ])
                        ->columns(2),

                    Wizard\Step::make('Contact Information')
                        ->icon('heroicon-m-phone')
                        ->description('Add contact details')
                        ->schema([
                            TextInput::make('phone')
                                ->tel()
                                ->maxLength(20),
                            TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('website')
                                ->url()
                                ->maxLength(255),
                        ])
                        ->columns(2),

                    Wizard\Step::make('Location')
                        ->icon('heroicon-m-map-pin')
                        ->description('Set organization location')
                        ->schema([
                            Textarea::make('address')
                                ->label('Address')
                                ->rows(3)
                                ->columnSpanFull(),
                            ...IndonesiaRegionHelper::completeRegionFields(),
                        ])
                        ->columns(2),
                ])
                ->skippable()
                ->persistStepInQueryString()
            ]);
    }
}
