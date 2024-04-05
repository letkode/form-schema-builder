<?php

namespace Letkode\FormSchemaBuilder\Traits\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait entity updated at trait.
 */
trait UpdatedAtTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTime $updatedAt;

    #[ORM\PrePersist]
    public function updatedAtPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}
