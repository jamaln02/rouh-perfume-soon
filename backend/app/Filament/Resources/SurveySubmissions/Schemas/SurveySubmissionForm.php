<?php

namespace App\Filament\Resources\SurveySubmissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SurveySubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('age')
                    ->required()
                    ->numeric(),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required(),
                TextInput::make('location')
                    ->required(),
                Select::make('perfume_quality')
                    ->options(['original' => 'Original', 'tarkib' => 'Tarkib', 'both' => 'Both'])
                    ->required(),
                Textarea::make('favorite_perfumes')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('purchase_frequency')
                    ->options([
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'biannually' => 'Biannually',
            'yearly' => 'Yearly',
            'rarely' => 'Rarely',
        ])
                    ->default(null),
                Textarea::make('main_problem')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('price_range')
                    ->default(null),
                Select::make('perfume_type')
                    ->options(['oriental' => 'Oriental', 'western' => 'Western', 'mixed' => 'Mixed', 'other' => 'Other'])
                    ->default(null),
                Textarea::make('perfume_type_other')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('discovery_method')
                    ->options([
            'social' => 'Social',
            'friends' => 'Friends',
            'store' => 'Store',
            'influencers' => 'Influencers',
            'ads' => 'Ads',
        ])
                    ->default(null),
                Textarea::make('wishlist')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('discount_code')
                    ->default(null),
                Toggle::make('is_used')
                    ->required(),
                TextInput::make('ip_address')
                    ->default(null),
            ]);
    }
}
