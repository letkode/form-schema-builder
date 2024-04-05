<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\OptionGeneral;

class OptionGeneralRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return OptionGeneral::class;
    }
}