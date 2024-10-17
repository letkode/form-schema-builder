<?php

namespace Letkode\FormSchemaBuilder\Service;

use Doctrine\ORM\EntityManagerInterface;
use Letkode\FormSchemaBuilder\Entity\Form;
use Letkode\FormSchemaBuilder\Enum\TypeFilterFormStructureEnum;
use Letkode\FormSchemaBuilder\Repository\FormRepository;
use Letkode\FormSchemaBuilder\Utils\DataOptionUtil;
use Letkode\FormSchemaBuilder\Utils\FormatterValueTypeUtils;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormService
{

    private Form $form;

    private array $parameters = [];

    private array $generalParameters = [];

    private array $configOptions = [];

    private array $fieldsArray = [];

    private ?string $attrActionCheck = null;

    private TypeFilterFormStructureEnum $typeFilterSection;

    private array $filterSectionItems = [];

    private TypeFilterFormStructureEnum $typeFilterGroup;

    private array $filterGroupItems = [];

    private bool $onlyFieldStructure = false;

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly FormRepository $formRepository, private readonly ParameterBag $parameterBag)
    {
        $this->setConfigOptions([
            'namespace_entity' => $this->parameterBag->get('form_schema_builder.namespace_entity')
        ]);
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function setForm(Form $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function getGeneralParameters(): array
    {
        return $this->generalParameters;
    }

    public function setGeneralParameters(array $generalParameters): self
    {
        $this->generalParameters = $generalParameters;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Load Form Object by Tag
     **/
    public function load(string $tag): self
    {
        if (null === $object = $this->formRepository->findOneBy(['tag' => $tag])) {
            throw new NotFoundHttpException(sprintf('Form %s not found', $tag));
        }

        $this->form = $object;

        return $this;
    }

    /**
     * Get structure form in array format
     */
    public function structure(): array
    {
        $formStructure = $this
            ->getForm()
            ->setFilterSections($this->getFilterSectionItems(), $this->getTypeFilterSection())
            ->setFilterGroups($this->getFilterGroupItems(), $this->getTypeFilterGroup())
            ->toArray();

        if ($this->isOnlyFieldStructure()) {
            return $this->getArrayFields();
        }

        return $formStructure;
    }

    public function getConfigOptions(): array
    {
        return $this->configOptions;
    }

    public function setConfigOptions(array $configOptions): self
    {
        $this->configOptions = $configOptions;

        return $this;
    }

    public function getArrayFields(): array
    {
        return $this->fieldsArray;
    }

    public function getAttrActionCheck(): ?string
    {
        return $this->attrActionCheck;
    }

    public function setAttrActionCheck(?string $attrActionCheck): self
    {
        $this->attrActionCheck = $attrActionCheck;

        return $this;
    }

    public function getTypeFilterSection(): TypeFilterFormStructureEnum
    {
        return $this->typeFilterSection;
    }

    public function setTypeFilterSection(TypeFilterFormStructureEnum $typeFilterSection): self
    {
        $this->typeFilterSection = $typeFilterSection;

        return $this;
    }

    public function getFilterSectionItems(): array
    {
        return $this->filterSectionItems;
    }

    public function setFilterSectionItems(array $filterSectionItems): self
    {
        $this->filterSectionItems = $filterSectionItems;

        return $this;
    }

    public function getTypeFilterGroup(): TypeFilterFormStructureEnum
    {
        return $this->typeFilterGroup;
    }

    public function setTypeFilterGroup(TypeFilterFormStructureEnum $typeFilterGroup): self
    {
        $this->typeFilterGroup = $typeFilterGroup;

        return $this;
    }

    public function getFilterGroupItems(): array
    {
        return $this->filterGroupItems;
    }

    public function setFilterGroupItems(array $filterGroupItems): self
    {
        $this->filterGroupItems = $filterGroupItems;

        return $this;
    }

    public function isOnlyFieldStructure(): bool
    {
        return $this->onlyFieldStructure;
    }

    public function setOnlyFieldStructure(bool $onlyFieldStructure): self
    {
        $this->onlyFieldStructure = $onlyFieldStructure;

        return $this;
    }

    /**
     * Processes form fields based on their type, attributes and parameters
     */
    private function processFields(array &$arrayForm): void
    {
        $configOptions = $this->getConfigOptions();

        foreach ($arrayForm['sections'] as &$section) {
            foreach ($section['groups'] as $keyGroup => &$group) {
                foreach ($group['fields'] as $keyField => &$field) {
                    $fieldAttributes = $field['attributes'];
                    $defaultValueField =  $fieldAttributes['default_value'] ?? null;

                    $required = false;
                    $readonly = false;
                    if (null !== $attrActionCheck = $this->getAttrActionCheck()) {
                        if (!($fieldAttributes[$attrActionCheck]['enabled'] ?? false)) {
                            unset($group['fields'][$keyField]);
                            continue;
                        }

                        $required = $fieldAttributes[$attrActionCheck]['required'] ?? false;
                        $readonly = $fieldAttributes[$attrActionCheck]['readonly'] ?? false;
                    }

                    if ($fieldAttributes['set_options_values'] ?? false) {
                        $field['parameters']['options'] = (new DataOptionUtil())
                            ->setConfigs($configOptions)
                            ->setEntityManager($this->entityManager)
                            ->setOptionsParameters($field['parameters']['set_options'])
                            ->get();
                    }

                    $field['attributes'] += ['required' => $required, 'readonly' => $readonly];

                    // Formatter default value type Date
                    $field['default_value'] = (new FormatterValueTypeUtils(
                        $field['type'],
                        $defaultValueField)
                    )->format();


                    $this->fieldsArray[$field['tag']] = $field;
                }

                if (empty($group['fields'])) {
                    unset($section['groups'][$keyGroup]);
                }
            }
        }
    }
}