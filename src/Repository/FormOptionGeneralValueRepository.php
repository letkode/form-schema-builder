<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\FormOptionGeneralValue;

class FormOptionGeneralValueRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return FormOptionGeneralValue::class;
    }
}