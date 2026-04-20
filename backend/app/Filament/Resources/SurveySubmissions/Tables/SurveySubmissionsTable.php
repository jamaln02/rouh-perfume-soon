<?php

namespace App\Filament\Resources\SurveySubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveySubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gender')
                    ->badge(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('perfume_quality')
                    ->badge(),
                TextColumn::make('purchase_frequency')
                    ->badge(),
                TextColumn::make('price_range')
                    ->searchable(),
                TextColumn::make('perfume_type')
                    ->badge(),
                TextColumn::make('discovery_method')
                    ->badge(),
                TextColumn::make('discount_code')
                    ->searchable(),
                IconColumn::make('is_used')
                    ->boolean(),
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
