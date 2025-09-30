<?php

namespace App\Filament\Resources\Organizations\Schemas;

use App\Helpers\IndonesiaRegionHelper;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class OrganizationRegisterForm
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
                            TextInput::make('brand')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            FileUpload::make('logo')
                                ->image()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                                ->directory('organization-logos')
                                ->helperText('Max file size: 2MB. Allowed file types: jpg, jpeg, png.')
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
                    ->persistStepInQueryString()
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button
                        type="submit"
                    >
                        <x-slot name="icon">
                            <x-solar-diskette-bold class="h-5 w-5" />
                        </x-slot>
                        Register Organization
                    </x-filament::button>
                BLADE))),
            ]);
    }
}
