<?php

namespace App\Filament\Resources\Organizations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    // Logo
                    ImageColumn::make('logo')
                        ->getStateUsing(fn ($record) => $record->logo ? route('organization.logo', ['path' => $record->logo]) : null)
                        ->height(40)
                        ->width(40)
                        ->defaultImageUrl(url('/images/placeholder-logo.svg'))
                        ->circular()
                        ->grow(false),
                    
                    // Basic Information
                    Stack::make([
                        TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->size('md')
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('code')
                            ->color('gray')
                            ->size('sm')
                            ->searchable()
                            ->prefix('ID: '),
                    ])->grow(),
                    
                    // Contact Information
                    Stack::make([
                        TextColumn::make('phone')
                            ->icon('heroicon-o-phone')
                            ->color('gray')
                            ->size('sm'),
                        TextColumn::make('email')
                            ->icon('heroicon-o-envelope')
                            ->color('gray')
                            ->size('sm'),
                        TextColumn::make('website')
                            ->icon('heroicon-o-globe-alt')
                            ->color('blue')
                            ->size('sm')
                    ])->visibleFrom('md')
                    ->grow(),
                    
                    // Status
                    TextColumn::make('is_active')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            '1' => 'success',
                            '0' => 'danger',
                        })
                        ->formatStateUsing(fn (string $state): string => $state ? 'Active' : 'Inactive')
                        ->grow(false),
                ])->from('sm'),
                
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
