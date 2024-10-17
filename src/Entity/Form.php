<?php
namespace Letkode\FormSchemaBuilder\Entity;

use JetBrains\PhpStorm\ArrayShape;
use Letkode\FormSchemaBuilder\Enum\TypeFilterFormStructureEnum;
use Letkode\FormSchemaBuilder\Repository\FormRepository;
use Letkode\FormSchemaBuilder\Traits\Entity\DefaultLangTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\LangTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\ParameterTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\TranslationTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormRepository::class)]
class Form
{
    use LangTrait;
    use TranslationTrait;
    use DefaultLangTrait;
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

    private array $filterSections = [];

    private array $filterGroups = [];

    public function __construct()
    {
        $this->setUuid();
        $this->sections = new ArrayCollection();
    }

    public function toArray(): array
    {
        $lang = $this->getLang();
        $filterSections = $this->getFilterSections();
        $filterGroups = $this->getFilterGroups();

        $sections = [];
        /** @var FormSection $section */
        foreach ($this->getSections() as $section) {
            if ($filterSections['items'] !== []) {
                if ($filterSections['type'] === TypeFilterFormStructureEnum::INCLUDE && !in_array(
                        $section->getTag(),
                        $filterSections['items']
                    )) {
                    continue;
                }

                if ($filterSections['type'] === TypeFilterFormStructureEnum::EXCLUDE && in_array(
                        $section->getTag(),
                        $filterSections['items']
                    )) {
                    continue;
                }
            }

            $sections[$section->getId()] = $section
                ->setLang($lang)
                ->setFilterGroups($filterGroups['items'], $filterGroups['type'])
                ->toArray();
        }

        return [
            'id' => $this->getUuid(),
            'name' => $this->getName(),
            'tag' => $this->getTag(),
            'lang' => $this->getLang(),
            'default_lang' => $this->getDefaultLang(),
            'parameters' => $this->getParameters(),
            'enabled' => $this->isEnabled(),
            'sections' => array_values($sections),
        ];
    }

    public function toArrayFields(): array
    {
        $lang = $this->getLang();

        $fields = [];
        /** @var FormSection $section */
        foreach ($this->getSections() as $section) {
            if (!empty($onlySections) && !in_array($section->getTag(), $onlySections)) {
                continue;
            }

            $section->setLang($lang);
            /** @var FormGroup $group */
            foreach ($section->getGroups() as $group) {
                $groupArray = $group->setLang($lang)->toArray();
                $fields = array_merge($fields, $groupArray['fields']);
            }
        }

        return $fields;
    }

    public function getFieldsArrayCollections(): ArrayCollection
    {
        $lang = $this->getLang();

        $fields = new ArrayCollection();
        /** @var FormSection $section */
        foreach ($this->getSections() as $section) {

            $section->setLang($lang);
            /** @var FormGroup $group */
            foreach ($section->getGroups() as $group) {

                $group->setLang($lang);
                /** @var FormField $field */
                foreach ($group->getFields() as $field) {
                    $field->setLang($lang);

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

    #[ArrayShape([
        'items' => 'array',
        'type' => TypeFilterFormStructureEnum::class
    ])]
    public function getFilterSections(): array
    {
        return $this->filterSections;
    }

    public function setFilterSections(array $items, TypeFilterFormStructureEnum $typeFilter): self
    {
        $this->filterSections = ['items' => $items, 'type' => $typeFilter];

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
