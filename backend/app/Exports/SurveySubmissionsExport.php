<?php

namespace App\Exports;

use App\Models\SurveySubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveySubmissionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SurveySubmission::all();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'رقم الموبايل',
            'الإيميل',
            'العمر',
            'الجنس',
            'المدينة',
            'نوع العطر',
            'كود الخصم',
            'تاريخ الإرسال',
        ];
    }

    public function map($record): array
    {
        return [
            $record->name,
            $record->phone,
            $record->email,
            $record->age,
            $record->gender === 'male' ? 'ذكر' : 'أنثى',
            $record->location,
            match ($record->perfume_quality) {
                'original' => 'أورجينال',
                'tarkib'   => 'تركيبات',
                'both'     => 'الاثنين',
                default    => '-',
            },
            $record->discount_code,
            $record->created_at->format('d/m/Y H:i'),
        ];
    }
}
