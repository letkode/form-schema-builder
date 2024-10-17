<?php

namespace Letkode\FormSchemaBuilder\Enum;

enum TypeFilterFormStructureEnum: string
{
    case INCLUDE = 'include';
    case EXCLUDE = 'exclude';

    public static function strToCase(string $value): self
    {
        return match ($value) {
            'include' => self::INCLUDE,
            'exclude' => self::EXCLUDE
        };
    }

}
