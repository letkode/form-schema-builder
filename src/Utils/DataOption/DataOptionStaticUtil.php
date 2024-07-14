<?php

namespace Letkode\FormSchemaBuilder\Utils\DataOption;

use Letkode\FormSchemaBuilder\Interface\DataOptionInterface;
use Letkode\FormSchemaBuilder\Utils\DataOptionUtil;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DataOptionStaticUtil extends DataOptionUtil implements DataOptionInterface
{
    public function get(array $paramsAttr = []): array
    {
        return $paramsAttr['options'] ?? throw new BadRequestException(
            'Options undefined in field parameters'
        );
    }
}