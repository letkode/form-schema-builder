<?php
namespace Letkode\FormSchemaBuilder\Entity;

use Letkode\FormSchemaBuilder\Repository\FormRepository;
use Letkode\FormSchemaBuilder\Traits\Entity\ParameterTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormRepository::class)]
class Form
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

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    private bool $enabled;

    #[ORM\OneToMany(mappedBy: 'form', targetEntity: FormSection::class)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $sections;

    public function __construct()
    {
        $this->setUuid();
        $this->sections = new ArrayCollection();
    }

    public function toArray(?array $onlySections = null): array
    {
        $sections = [];
        /** @var FormSection $section */
        foreach ($this->getSections() as $section) {
            if (is_array($onlySections) && !in_array($section->getTag(), $onlySections)) {
                continue;
            }

            $sections[$section->getId()] = $section->toArray();
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'tag' => $this->getTag(),
            'parameters' => $this->getParameters(),
            'enabled' => $this->isEnabled(),
            'sections' => array_values($sections),
            'uuid' => $this->getUuid(),
        ];
    }

    public function toArrayFields(): array
    {
        $fields = [];
        /** @var FormSection $section */
        foreach ($this->getSections() as $section) {
            if (!empty($onlySections) && !in_array($section->getTag(), $onlySections)) {
                continue;
            }

            /** @var FormGroup $group */
            foreach ($section->getGroups() as $group) {
                $groupArray = $group->toArray();
                $fields = array_merge($fields, $groupArray['fields']);
            }
        }

        return $fields;
    }

    public function getFieldsArrayCollections(): ArrayCollection
    {
        $fields = new ArrayCollection();
        /** @var FormSection $section */
        foreach ($this->getSections() as $section) {

            /** @var FormGroup $group */
            foreach ($section->getGroups() as $group) {

                /** @var FormField $field */
                foreach ($group->getFields() as $field) {
                    $fields->add($field);
                }
            }
        }

        return $fields;
    }

    public function getFieldsCheckAttribute(string $attr): array
    {
        $fields = array_filter(
            $this->toArrayFields(),
            function($f) use ($attr) {

                if (is_array($f['attributes'][$attr])) {
                    return $f['attributes'][$attr]['enabled'];
                }

                return $f['attributes'][$attr];
            }
        );

        return array_combine(
            array_column($fields, 'tag'),
            array_values($fields)
        );
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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        
        return $this;
    }

    public function getSections(): Collection|ArrayCollection
    {
        return $this->sections;
    }

    public function addSection(FormSection $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setForm($this);
        }

        return $this;
    }

    public function removeSection(FormSection $section): self
    {
        if ($this->sections->removeElement($section)) {
            if ($section->getForm() === $this) {
                $section->setForm(null);
            }
        }

        return $this;
    }
}
