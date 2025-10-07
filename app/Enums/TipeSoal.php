<?php

namespace App\Enums;

enum TipeSoal: string
{
    case TA = 'ta';
    case FITB = 'fitb';
    case JURNAL = 'jurnal';
    case MANDIRI = 'mandiri';
    case TK = 'tk';
    case TP = 'tp';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getModelClass(TipeSoal $type): string
    {
        return match ($type) {
            self::TP => \App\Models\SoalTp::class,
            self::TA => \App\Models\SoalTa::class,
            self::TK => \App\Models\SoalTk::class,
            self::JURNAL => \App\Models\SoalJurnal::class,
            self::MANDIRI => \App\Models\SoalMandiri::class,
            self::FITB => \App\Models\SoalFitb::class,
        };
    }
}
