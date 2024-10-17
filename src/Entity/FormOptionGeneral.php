<?php

namespace Letkode\FormSchemaBuilder\Entity;

use Letkode\FormSchemaBuilder\Repository\FormOptionGeneralRepository;
use Letkode\FormSchemaBuilder\Traits\Entity\LangTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\TranslationTrait;
use Letkode\FormSchemaBuilder\Traits\Entity\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: FormOptionGeneralRepository::class)]
#[ORM\Table(name: '`form_option_general`')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
class FormOptionGeneral
{
    use LangTrait;
    use TranslationTrait;
    use UuidTrait;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    private string $tag;

    #[ORM\Column(type: Types::JSON)]
    private array $parameters;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: FormOptionGeneralValue::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC', 'id' => 'ASC'])]
    private Collection $values;

    public function toArray(bool $withValues = true): array
    {
        $array = [
            'id' => $this->getUuid(),
            'name' => $this->getName(),
            'tag' => $this->getTag(),
            'parameters' => $this->getParameters()
        ];

        if ($withValues) {
            return array_merge($array, ['values' => $this->getValuesToArray()]);
        }

        return $array;
    }

    public function __construct()
    {
        $this->values = new ArrayCollection();
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

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter($key, $default = null)
    {
        $parameters = $this->getParameters();

        return $parameters[$key] ?? $default;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = array_replace_recursive($this->parameters, $parameters);

        return $this;
    }

    public function addValue(FormOptionGeneralValue $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    public function removeValue(FormOptionGeneralValue $value): void
    {
        $this->values->removeElement($value);
    }

    public function getValues(): Collection
    {
        return $this->values;
    }

    public function getValuesToArray(array $parameters): array
    {
        $key = $parameters['_key'] ?? 'id';
        $text = $parameters['_text'] ?? 'text';

        $values = $parameters['_with_all_option']
            ? [$parameters['_all_option_params']['id'] => $parameters['_all_option_params']['text']]
            : [];

        /** @var FormOptionGeneralValue $value */
        foreach ($this->getValues() as $value) {
            if (!$value->isEnabled()) {
                continue;
            }
            $data = $value->toArray(false);

            $values[$data[$key] ?? $data['id']] = $data[$text] ?? $data['text'];
        }

        return $values;
    }
}
