<?php

namespace Letkode\FormSchemaBuilder\Entity;

use JetBrains\PhpStorm\ArrayShape;
use Letkode\FormSchemaBuilder\Enum\TypeFilterFormStructureEnum;
use Letkode\FormSchemaBuilder\Repository\FormSectionRepository;
use Letkode\FormSchemaBuilder\Traits\Entity\LangTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\ParameterTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\TranslationTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormSectionRepository::class)]
class FormSection
{
    use LangTrait;
    use TranslationTrait;
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

    #[ORM\ManyToOne(targetEntity: Form::class, inversedBy: 'sections')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    private ?Form $form;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: FormGroup::class)]
    #[ORM\OrderBy(['position' => 'ASC', 'id' => 'ASC'])]
    private Collection $groups;

    private array $filterGroups = [];

    public function __construct()
    {
        $this->setUuid();
        $this->groups = new ArrayCollection();
    }

    public function toArray(): array
    {
        $filterGroups = $this->getFilterGroups();
        $groups = [];
        /** @var FormGroup $group */
        foreach ($this->getGroups() as $group) {
            if ($filterGroups['items'] !== []) {
                if ($filterGroups['type'] === TypeFilterFormStructureEnum::INCLUDE && !in_array(
                        $group->getTag(),
                        $filterGroups['items']
                    )) {
                    continue;
                }

                if ($filterGroups['type'] === TypeFilterFormStructureEnum::EXCLUDE && in_array(
                        $group->getTag(),
                        $filterGroups['items']
                    )) {
                    continue;
                }
            }

            $groups[$group->getId()] = $group->toArray();
        }

        return [
            'id' => $this->getUuid(),
            'name' => $this->getName(),
            'tag' => $this->getTag(),
            'description' => $this->getDescription(),
            'parameters' => $this->getParameters(),
            'position' => $this->getPosition(),
            'enabled' => $this->isEnabled(),
            'groups' => array_values($groups),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->getTranslationByLang($this->getLang(), 'name', $this->name);
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
        return $this->getTranslationByLang($this->getLang(), 'description', $this->description);
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

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): self
    {
        $this->form = $form;
        
        return $this;
    }

    public function getGroups(): Collection|ArrayCollection
    {
        return $this->groups;
    }

    public function addGroup(FormGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setSection($this);
        }

        return $this;
    }

    public function removeGroup(FormGroup $group): self
    {
        if ($this->groups->removeElement($group)) {
            if ($group->getSection() === $this) {
                $group->setSection(null);
            }
        }

        return $this;
    }

    #[ArrayShape([
        'items' => 'array',
        'type' => TypeFilterFormStructureEnum::class
    ])]
    public function getFilterGroups(): array
    {
        return $this->filterGroups;
    }

    public function setFilterGroups(array $items, TypeFilterFormStructureEnum $typeFilter): self
    {
        $this->filterGroups = ['items' => $items, 'type' => $typeFilter];

        return $this;
    }
}
