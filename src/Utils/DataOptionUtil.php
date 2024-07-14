<?php

namespace Letkode\FormSchemaBuilder\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Letkode\FormSchemaBuilder\Utils\DataOption\DataOptionEntityUtil;
use Letkode\FormSchemaBuilder\Utils\DataOption\DataOptionGeneralUtil;
use Letkode\FormSchemaBuilder\Utils\DataOption\DataOptionStaticUtil;

class DataOptionUtil
{
    private array $field;

    private array  $parameters = [];

    private EntityManagerInterface $entityManager;

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    protected function getOptionsParameters(): array
    {
        return $this->parameters;
    }

    public function setOptionsParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function get(): array
    {
        $values = [];
        foreach ($this->getOptionsParameters() as $optionParams) {
            $dataOptionUtils = match ($optionParams['type']) {
                'static' => new DataOptionStaticUtil(),
                'entity' => new DataOptionEntityUtil(),
                'option_general' => new DataOptionGeneralUtil(),
            };

            $values += $dataOptionUtils->get($optionParams['params']);
        }

        return $values;
    }
}