<?php

namespace Letkode\FormSchemaBuilder\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Letkode\FormSchemaBuilder\Utils\DataOption\DataOptionEntityUtil;
use Letkode\FormSchemaBuilder\Utils\DataOption\DataOptionGeneralUtil;
use Letkode\FormSchemaBuilder\Utils\DataOption\DataOptionStaticUtil;

class DataOptionUtil
{
    private array  $optionsParameters = [];

    private EntityManagerInterface $entityManager;

    private array $configs = [];


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
        return $this->optionsParameters;
    }

    public function setOptionsParameters(array $optionsParameters): self
    {
        $this->optionsParameters = $optionsParameters;

        return $this;
    }

    protected function getConfig(string $key, mixed $default = null): mixed
    {
        $configs = $this->getConfigs();

        return $configs[$key] ?? $default;
    }

    protected function getConfigs(): array
    {
        return $this->configs;
    }

    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

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