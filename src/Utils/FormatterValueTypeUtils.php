<?php

namespace Letkode\FormSchemaBuilder\Utils;

readonly class FormatterValueTypeUtils
{

    public function __construct(
        private string $typeField,
        private mixed $defaultValue
    )
    {
    }

    public function format(): array
    {
        $defaultValue = $this->defaultValue;

        return match ($this->typeField) {
            'date' => (new \DateTime())->modify($defaultValue)->format('Y-m-d'),
            default => $defaultValue
        };
    }
}