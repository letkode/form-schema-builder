<?php

namespace Letkode\FormSchemaBuilder\Model;

/**
 * Abstract values model.
 */
abstract class AbstractValuesModel
{
    /**
     * @var array
     */
    protected static array $values = [];

    public static function get(string $key): string
    {
        return static::$values[$key] ?? '';
    }

    public static function getArray(string $key): array
    {
        return static::$values[$key] ?? [];
    }

    public static function getValues(): array
    {
        return static::$values;
    }

    public static function getValuesWithExcludes(array $exclude = []): array
    {
        $values = static::getValues();

        foreach ($exclude as $item) {
            if (isset($values[$item])) {
                unset($values[$item]);
            }
        }

        return $values;
    }

    public static function getValuesListOptions(array $parameters = [], array $parametersGenerals = []): array
    {
        $exclude = $parameters['exclude'] ?? [];

        return self::getValuesWithExcludes($exclude);
    }

    protected static function convertList(array $values): array
    {
        return array_map(fn($value, $key) => ['id' => $key, 'text' => $value], $values);
    }
}
