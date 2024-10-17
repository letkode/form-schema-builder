<?php

namespace Letkode\FormSchemaBuilder\DependencyInjection;

use Letkode\FormSchemaBuilder\Entity\Form;
use Letkode\FormSchemaBuilder\Entity\FormField;
use Letkode\FormSchemaBuilder\Entity\FormGroup;
use Letkode\FormSchemaBuilder\Entity\FormSection;
use Letkode\FormSchemaBuilder\Entity\FormOptionGeneral;
use Letkode\FormSchemaBuilder\Entity\FormOptionGeneralValue;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('form_schema_builder');
        $rootNode = $treeBuilder->getRootNode();

        $this->addParameterInitLibrary($rootNode);
        $this->addMappingEntityRequired($rootNode);

        return $treeBuilder;
    }

    private function addParameterInitLibrary(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('namespace_entity')
                    ->cannotBeEmpty()
                    ->defaultValue('App\Entity')
                ->end()
                ->arrayNode('custom_form_field')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ->end();
    }

    private function addMappingEntityRequired(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('doctrine')
                ->children()
                    ->arrayNode('orm')
                        ->children()
                            ->arrayNode('mappings')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode(Form::class)->end()
                                    ->scalarNode(FormSection::class)->end()
                                    ->scalarNode(FormGroup::class)->end()
                                    ->scalarNode(FormField::class)->end()
                                    ->scalarNode(FormOptionGeneral::class)->end()
                                    ->scalarNode(FormOptionGeneralValue::class)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}