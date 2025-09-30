<?php

namespace App\Filament\Resources\Organizations\Schemas;

use App\Helpers\IndonesiaRegionHelper;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Basic Information')
                            ->icon('heroicon-m-building-office')
                            ->schema([
                                TextInput::make('code')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(50)
                                ->disabled(fn (Get $get): bool => $get('id') !== null)
                                ->dehydrated(), 

                                TextInput::make('name')
                                ->required()
                                ->maxLength(255), 

                                TextInput::make('brand')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                                ])
                            ->columns(2),

                        Section::make('Location')
                            ->icon('heroicon-m-map-pin')
                            ->schema([
                                Textarea::make('address')->label('Address')->rows(3)->columnSpanFull(), 
                                ...IndonesiaRegionHelper::completeRegionFields()
                                ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Contact Information')
                            ->icon('heroicon-m-phone')
                            ->schema([
                                TextInput::make('phone')->tel()->maxLength(20), 
                                TextInput::make('email')->label('Email address')->email()->maxLength(255), 
                                TextInput::make('website')->url()->maxLength(255)
                            ]),
                        Section::make('Settings')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                                FileUpload::make('logo')
                                ->image()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                                ->directory('organization-logos')
                                ->helperText('Max file size: 2MB. Allowed file types: jpg, jpeg, png.')
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])

            ->columns(3);
    }
}
