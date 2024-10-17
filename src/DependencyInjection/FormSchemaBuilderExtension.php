<?php

namespace Letkode\FormSchemaBuilder\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class FormSchemaBuilderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $container->setParameter('form_schema_builder.' . $key, $value);
        }
    }
}