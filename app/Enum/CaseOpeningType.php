<?php

namespace App\Enum;

enum CaseOpeningType: string
{
    case CASE = 'case';
    case WHEEL = 'wheel';

    /**
     * @return string[]
     */
    public static function stringCases(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    public function getView(): string
    {
        return match ($this) {
            CaseOpeningType::CASE => 'obs.case_opening.show',
            CaseOpeningType::WHEEL => 'obs.case_opening.show_wheel',
        };
    }
}
