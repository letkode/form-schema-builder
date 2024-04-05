<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\FormField;

class FormFieldRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return FormField::class;
    }
}