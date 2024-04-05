<?php

namespace Letkode\FormSchemaBuilder\Traits\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected DateTime $createdAt;

    #[ORM\PrePersist]
    public function createdAtPrePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
