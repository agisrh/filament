<?php

namespace App\Filament\Resources\Organizations\Schemas;

use App\Models\Organization;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrganizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('logo')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('website')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('region_code')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Organization $record): bool => $record->trashed()),
            ]);
    }
}
