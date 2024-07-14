<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\FormOptionGeneral;

class FormOptionGeneralRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return FormOptionGeneral::class;
    }
}