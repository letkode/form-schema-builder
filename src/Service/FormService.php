<?php

namespace Letkode\FormSchemaBuilder\Service;

use Doctrine\ORM\EntityManagerInterface;
use Letkode\FormSchemaBuilder\Entity\Form;
use Letkode\FormSchemaBuilder\Repository\FormRepository;
use Letkode\FormSchemaBuilder\Utils\DataOptionUtil;
use Letkode\FormSchemaBuilder\Utils\FormatterValueTypeUtils;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormService
{
    private EntityManagerInterface $entityManager;
    private Form $form;
    private FormRepository $repository;
    private array $parameters = [];
    private array $generalParameters = [];
    private array $configOptions = [];
    private array $fieldsArray = [];
    private ?string $attrActionCheck = null;
    private array $filterSections = ['filter' => 'include', 'sections' => []];
    private array $filterGroups = ['filter' => 'include', 'groups' => []];
    private array $onlySections = [];
    private array $onlyGroups = [];
    private bool $onlyFieldStructure = false;
    private static array $typeFieldsWithOptions = [
        'list',
        'list-group',
        'list-multiple',
        'list-group-multiple',
        'matrix-simple',
    ];

    public function __construct(EntityManagerInterface $entityManager, FormRepository $formRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $formRepository;
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
     */
    public function load(string $tag): self
    {
        if (null === $object = $this->repository->findOneBy(['tag' => $tag])) {
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
            ->setOnlySections($this->getOnlySections())
            ->setOnlyGroups($this->getOnlyGroups())
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

    public function getOnlySections(): array
    {
        return $this->onlySections;
    }

    public function setOnlySections(array $onlySections): self
    {
        $this->onlySections = $onlySections;

        return $this;
    }

    public function getOnlyGroups(): array
    {
        return $this->onlyGroups;
    }

    public function setOnlyGroups(array $onlyGroups): self
    {
        $this->onlyGroups = $onlyGroups;

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
        $fieldOptions = $configOptions['fields'] ?? [];

        foreach ($arrayForm['sections'] as &$section) {
            foreach ($section['groups'] as $keyGroup => &$group) {
                foreach ($group['fields'] as $keyField => &$field) {

                    $defaultValueField =  $field['attributes']['default_value'] ?? null;

                    if (null !== $attrActionCheck = $this->getAttrActionCheck()) {
                        if (is_array($field['attributes'][$attrActionCheck])) {
                            $isActive = $field['attributes'][$attrActionCheck]['enabled'] ?? false;
                        }else{
                            $isActive = $field['attributes'][$attrActionCheck] ?? false;
                        }

                        if (!$isActive) {
                            unset($group['fields'][$keyField]);
                            continue;
                        }
                    }

                    // Set Data Option
                    if (in_array($field['type'], self::$typeFieldsWithOptions)) {
                        $field['parameters']['options'] = (new DataOptionUtil())
                            ->setEntityManager($this->entityManager)
                            ->setOptionsParameters($field['parameters']['set_options'])
                            ->get();
                    }

                    // Formatter default value type Date
                    $field['default_value'] = (new FormatterValueTypeUtils(
                        $field['type'],
                        $defaultValueField)
                    )->format();

                    if ($fieldOptions['ignore_required'] ?? false) {
                        $field['attributes']['required'] = false;
                    }

                    $this->fieldsArray[$field['tag']] = $field;
                }

                if (empty($group['fields'])) {
                    unset($section['groups'][$keyGroup]);
                }
            }
        }
    }
}