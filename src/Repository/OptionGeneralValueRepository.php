<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\OptionGeneralValue;

class OptionGeneralValueRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return OptionGeneralValue::class;
    }
}