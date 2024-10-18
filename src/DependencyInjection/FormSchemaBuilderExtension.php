<?php

namespace Letkode\FormSchemaBuilder\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class FormSchemaBuilderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        if (!file_exists(__DIR__ . '/../Resources/config/form_schema_builder.yaml')) {
            throw new \Exception(
                'Archivo form_schema_builder.yaml no encontrado en ' . __DIR__ . '/../Resources/config/form_schema_builder.yaml'
            );
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('form_schema_builder.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $container->setParameter('form_schema_builder.' . $key, $value);
        }
    }

    public function getAlias(): string
    {
        return 'form_schema_builder';
    }
}