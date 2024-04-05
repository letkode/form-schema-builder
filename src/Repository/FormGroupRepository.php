<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\FormGroup;

class FormGroupRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return FormGroup::class;
    }
}