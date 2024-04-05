<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Letkode\FormSchemaBuilder\Entity\FormSection;

class FormSectionRepository extends AbstractBaseRepository
{
    protected function getEntityClassAssoc(): string
    {
        return FormSection::class;
    }
}