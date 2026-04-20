<?php

namespace App\Filament\Resources\SurveySubmissions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SurveySubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('phone'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('age')
                    ->numeric(),
                TextEntry::make('gender')
                    ->badge(),
                TextEntry::make('location'),
                TextEntry::make('perfume_quality')
                    ->badge(),
                TextEntry::make('favorite_perfumes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('purchase_frequency')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('main_problem')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('price_range')
                    ->placeholder('-'),
                TextEntry::make('perfume_type')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('perfume_type_other')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('discovery_method')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('wishlist')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('discount_code')
                    ->placeholder('-'),
                IconEntry::make('is_used')
                    ->boolean(),
                TextEntry::make('ip_address')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
