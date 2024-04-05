<?php

namespace Letkode\FormSchemaBuilder\Traits\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
    #[ORM\Column(type: Types::STRING, unique: true, nullable: true)]
    #[SerializedName('id')]
    #[Groups(['id'])]
    protected ?string $uuid;


    #[ORM\PrePersist]
    public function uuidPrePersist(): void
    {
        $this->uuid = Uuid::v4()->toRfc4122();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(): self
    {
        $this->uuid = Uuid::v4()->toRfc4122();

        return $this;
    }
}
