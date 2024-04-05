<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\Form;

class FormRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return Form::class;
    }
}