<?php

namespace Letkode\FormSchemaBuilder\Enum;

enum FieldTypeEnum: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case TEXT = 'text';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case IDENTIFICATION = 'identification';
    case DATE = 'date';
    case TIME = 'time';
    case FILE = 'file';
    case LIST = 'list';
    case LIST_GROUP = 'list-group';
    case LIST_MULTIPLE = 'list-multiple';
    case LIST_GROUP_MULTIPLE = 'list-group-multiple';
    case MATRIX_SIMPLE = 'matrix-simple';
    case HIDDEN = 'hidden';
    case SWITCH = 'switch';


    public static function strToCase(string $value): self
    {
        return match ($value) {
            'string' => self::STRING,
            'number' => self::NUMBER,
            'text' => self::TEXT,
            'email' => self::EMAIL,
            'phone' => self::PHONE,
            'identification' => self::IDENTIFICATION,
            'date' => self::DATE,
            'time' => self::TIME,
            'file' => self::FILE,
            'list' => self::LIST,
            'list-group' => self::LIST_GROUP,
            'list-multiple' => self::LIST_MULTIPLE,
            'list-group-multiple' => self::LIST_GROUP_MULTIPLE,
            'matrix-simple' => self::MATRIX_SIMPLE,
            'hidden' => self::HIDDEN,
            'switch' => self::SWITCH,
        };
    }

}
