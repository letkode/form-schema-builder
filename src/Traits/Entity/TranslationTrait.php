<?php

namespace Letkode\FormSchemaBuilder\Traits\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TranslationTrait
{
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $translations = [];

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function setTranslations(array $translations): self
    {
        $this->translations = $translations;

        return $this;
    }

    public function getTranslationByLang(
        string $lang = 'es',
        string $key = 'name',
        ?string $defaultValue = null
    ): ?string {
        $translations = $this->getTranslations();

        return $translations[$lang][$key] ?? $defaultValue;
    }
}
