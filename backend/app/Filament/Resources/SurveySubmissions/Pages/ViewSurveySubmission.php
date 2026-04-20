<?php

namespace App\Filament\Resources\SurveySubmissions\Pages;

use App\Filament\Resources\SurveySubmissions\SurveySubmissionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSurveySubmission extends ViewRecord
{
    protected static string $resource = SurveySubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
