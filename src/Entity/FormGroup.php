<?php

namespace Letkode\FormSchemaBuilder\Entity;

use Letkode\FormSchemaBuilder\Repository\FormGroupRepository;
use Letkode\FormSchemaBuilder\Traits\Entity\ParameterTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormGroupRepository::class)]
class FormGroup
{
    use ParameterTrait;
    use UuidTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $tag;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $position;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    private bool $enabled;

    #[ORM\ManyToOne(targetEntity: FormSection::class, inversedBy: 'groups')]
    #[ORM\JoinColumn(name: 'section_id', referencedColumnName: 'id')]
    private ?FormSection $section;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: FormField::class)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $fields;

    public function __construct()
    {
        $this->setUuid();
        $this->fields = new ArrayCollection();
    }

    public function toArray(): array
    {
        $fields = [];
        /** @var FormField $field */
        foreach ($this->getFields() as $field) {
            $fields[$field->getId()] = $field->toArray();
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'tag' => $this->getTag(),
            'description' => $this->getDescription(),
            'parameters' => $this->getParameters(),
            'format' => $this->getTypeRenderGroup(),
            'position' => $this->getPosition(),
            'enabled' => $this->isEnabled(),
            'fields' => array_values($fields),
            'uuid' => $this->getUuid(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getSection(): ?FormSection
    {
        return $this->section;
    }

    public function setSection(?FormSection $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getFields(): Collection|ArrayCollection
    {
        return $this->fields;
    }

    public function addField(FormField $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
            $field->setGroup($this);
        }

        return $this;
    }

    public function removeField(FormField $field): self
    {
        if ($this->fields->removeElement($field)) {
            if ($field->getGroup() === $this) {
                $field->setGroup(null);
            }
        }

        return $this;
    }

    public function getTypeRenderGroup(): string
    {
        return $this->getParameter('type_render', 'simple');
    }
}
