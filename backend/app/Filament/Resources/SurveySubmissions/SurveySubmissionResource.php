<?php

namespace App\Filament\Resources\SurveySubmissions;

use App\Filament\Resources\SurveySubmissions\Pages\ListSurveySubmissions;
use App\Filament\Resources\SurveySubmissions\Pages\ViewSurveySubmission;
use App\Models\SurveySubmission;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;

class SurveySubmissionResource extends Resource
{
    protected static ?string $model = SurveySubmission::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-rectangle-stack';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                TextColumn::make('phone')->label('رقم الموبايل')->searchable()->sortable(),
                TextColumn::make('email')->label('الإيميل')->searchable(),
                TextColumn::make('age')->label('العمر')->sortable(),

                TextColumn::make('gender')
                    ->label('الجنس')
                    ->formatStateUsing(fn ($state) => $state === 'male' ? 'ذكر' : 'أنثى'),

                TextColumn::make('location')->label('المدينة'),

                TextColumn::make('perfume_quality')
                    ->label('نوع العطر')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'original' => 'أورجينال',
                        'tarkib'   => 'تركيبات',
                        'both'     => 'الاثنين',
                        default    => '-',
                    }),

                TextColumn::make('discount_code')
                    ->label('كود الخصم')
                    ->copyable()
                    ->color('success')
                    ->icon('heroicon-o-ticket'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإرسال')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                IconColumn::make('is_used')
                    ->label('مستخدم')
                    ->boolean(),
            ])

            ->defaultSort('created_at', 'desc')
            ->searchable()

            ->filters([
                SelectFilter::make('gender')
                    ->label('الجنس')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ]),

                SelectFilter::make('perfume_quality')
                    ->label('نوع العطر')
                    ->options([
                        'original' => 'أورجينال',
                        'tarkib'   => 'تركيبات',
                        'both'     => 'الاثنين',
                    ]),
            ])

->headerActions([
    Action::make('export')
        ->label('تصدير إلى Excel')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->action(function () {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\SurveySubmissionsExport,
                'استبيانات_روح.xlsx'
            );
        }),
])



;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveySubmissions::route('/'),
            'view'  => ViewSurveySubmission::route('/{record}'),
        ];
    }
}
