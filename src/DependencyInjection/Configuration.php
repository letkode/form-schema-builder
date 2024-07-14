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
        $treeBuilder = new TreeBuilder('app.doctrine');
        $rootNode =$treeBuilder->getRootNode();

        $this->addMappingEntityRequired($rootNode);

        return $treeBuilder;
    }

    private function addMappingEntityRequired(ArrayNodeDefinition $rootNode): void
    {
        $rootNode->children()
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
            ->end();
    }
}