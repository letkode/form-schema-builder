<?php

namespace Letkode\FormSchemaBuilder\Traits\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait DefaultLangTrait
{
    #[ORM\Column(type: Types::STRING, options: [
        'default' => 'es'
    ])]
    private string $defaultLang = 'es';

    public function getDefaultLang(): string
    {
        return $this->defaultLang;
    }

    public function setDefaultLang(string $defaultLang): self
    {
        $this->defaultLang = $defaultLang;

        return $this;
    }
}
