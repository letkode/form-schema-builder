<?php

namespace Letkode\FormSchemaBuilder\DependencyInjection;

use Letkode\FormSchemaBuilder\Entity\Form;
use Letkode\FormSchemaBuilder\Entity\FormField;
use Letkode\FormSchemaBuilder\Entity\FormGroup;
use Letkode\FormSchemaBuilder\Entity\FormSection;
use Letkode\FormSchemaBuilder\Entity\OptionGeneral;
use Letkode\FormSchemaBuilder\Entity\OptionGeneralValue;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('app.doctrine');

        $treeBuilder->getRootNode()
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
                                ->scalarNode(OptionGeneral::class)->end()
                                ->scalarNode(OptionGeneralValue::class)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}