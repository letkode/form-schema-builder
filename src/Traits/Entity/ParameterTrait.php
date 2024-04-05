<?php

namespace Letkode\FormSchemaBuilder\Traits\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait ParameterTrait
{
    #[ORM\Column(type: Types::JSON)]
    private array $parameters = [];

    public function setParameters(array $parameters): self
    {
        $this->parameters = array_replace_recursive($this->parameters, $parameters);

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string|int $key, mixed $default = null): mixed
    {
        if (method_exists($this, 'getParameters')) {
            $params = $this->getParameters();

            return $params[$key] ?? $default;
        }

        return $default;
    }
}
