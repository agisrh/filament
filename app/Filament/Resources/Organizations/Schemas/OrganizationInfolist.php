<?php

namespace App\Filament\Resources\Organizations\Schemas;

use App\Models\Organization;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([ 
                Section::make('Informasi Organisasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('code')
                                        ->label('Kode')
                                        ->placeholder('-'),
                                    
                                    TextEntry::make('brand')
                                        ->label('Brand')
                                        ->placeholder('-'),

                                    TextEntry::make('is_active')
                                        ->label('Status')
                                        ->badge()
                                        ->color(fn (Organization $record): string => $record->is_active ? 'success' : 'danger')
                                        ->formatStateUsing(fn (Organization $record): string => $record->is_active ? 'Aktif' : 'Tidak Aktif'),

                                ]),
                                Group::make([
                                      TextEntry::make('name')
                                        ->label('Nama')
                                        ->placeholder('-'),

                                     ImageEntry::make('logo')
                                        ->defaultImageUrl(asset('images/placeholder-logo.svg'))
                                        ->size(120)
                                        ->circular()
                                        ->hiddenLabel()
                                        ->alignLeft(),
                                ]),
                            ]),
                    ]),
                
                Section::make('Informasi Lokasi')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Alamat')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('province.name')
                                    ->label('Provinsi')
                                    ->placeholder('-'),
                                
                                TextEntry::make('city.name')
                                    ->label('Kota/Kabupaten')
                                    ->placeholder('-'),
                                
                                TextEntry::make('district.name')
                                    ->label('Kecamatan')
                                    ->placeholder('-'),
                                
                                TextEntry::make('village.name')
                                    ->label('Kelurahan/Desa')
                                    ->placeholder('-'),
                            ]),
                    ]),

                Section::make('Informasi Kontak')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('phone')
                                    ->label('Telepon')
                                    ->placeholder('-')
                                    ->icon('heroicon-o-phone'),
                                
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->placeholder('-')
                                    ->icon('heroicon-o-envelope'),
                                
                                TextEntry::make('website')
                                    ->label('Website')
                                    ->placeholder('-')
                                    ->icon('heroicon-o-globe-alt')
                                    ->url(fn (Organization $record): ?string => $record->website)
                                    ->openUrlInNewTab(),
                            ]),
                    ]),
                
                Section::make('Metadata')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat')
                                    ->dateTime('d/m/Y H:i:s')
                                    ->placeholder('-'),
                                
                                TextEntry::make('updated_at')
                                    ->label('Diperbarui')
                                    ->dateTime('d/m/Y H:i:s')
                                    ->placeholder('-'),
                                
                                TextEntry::make('deleted_at')
                                    ->label('Dihapus')
                                    ->dateTime('d/m/Y H:i:s')
                                    ->placeholder('-')
                                    ->visible(fn (Organization $record): bool => $record->trashed()),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
