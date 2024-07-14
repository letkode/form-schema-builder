<?php

namespace Letkode\FormSchemaBuilder\Entity;

use Letkode\FormSchemaBuilder\Enum\FieldTypeEnum;
use Letkode\FormSchemaBuilder\Repository\FormFieldRepository;
use Letkode\FormSchemaBuilder\Traits\Entity\ParameterTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\UuidTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\UniqueConstraint(fields: ['group', 'tag'])]
#[ORM\Entity(repositoryClass: FormFieldRepository::class)]
class FormField
{
    use ParameterTrait;
    use UuidTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $tag;

    #[ORM\Column(type: Types::STRING, enumType: FieldTypeEnum::class)]
    private FieldTypeEnum|string $type;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::JSON)]
    private array $attributes;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $position;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    private bool $enabled;

    #[ORM\ManyToOne(targetEntity: FormGroup::class, inversedBy: 'fields')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private ?FormGroup $group;

    const DEFAULT_ATTRIBUTES =  [
        'check_by_role' => ['enabled' => false, 'hierarchy' => true, 'roles_allow' => []],
        'type_value' => 'string',
        'required' => false,
        'unique' => false,
        'unique_params' => [
            'entity' => null,
            'method' => null
        ],
        'unique_entity' => null,
        'is_relationship_entity' => null,
        'column_table' => true,
        'hidden_form' => false,
        'show_bulk_upload_summary' => true,
        'rename' => null
    ];

    public function __construct()
    {
        $this->setUuid();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getName(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'tag' => $this->getTag(),
            'slug' => $this->getTag(),
            'description' => $this->getDescription(),
            'attributes' => $this->getAttributes(),
            'parameters' => $this->getParameters(),
            'position' => $this->getPosition(),
            'enabled' => $this->isEnabled(),
            'uuid' => $this->getUuid(),
            'placeholder' => $this->getParameter('placeholder'),
            'default_value' => $this->getParameter('default_value'),
            'values' => [],
            'style' => $this->getParameter('style', ['lg:w-6/12'])
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

    public function getType(): string
    {
        return $this->type->value;
    }

    public function setType(FieldTypeEnum|string $type): self
    {
        $this->type = is_string($type) ? FieldTypeEnum::strToCase($type) : $type;

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

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        $attributes = $this->getAttributes();

        return $attributes[$name] ?? $default;
    }

    public function getAttributes(): array
    {
        return array_replace_recursive(self::DEFAULT_ATTRIBUTES, $this->attributes);
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

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

    public function getGroup(): ?FormGroup
    {
        return $this->group;
    }

    public function setGroup(?FormGroup $group): self
    {
        $this->group = $group;

        return $this;
    }
}
