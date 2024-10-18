<?php

namespace Letkode\FormSchemaBuilder;

class Installer
{
    public static function install(): void
    {
        $currentWorkingDir = getcwd();
        $configDir = realpath($currentWorkingDir . '/config/packages');


        if ($configDir !== false) {
            $configFile = $configDir . '/form_schema_builder.yaml';

            if (!file_exists($configFile)) {
                copy(__DIR__ . '/../Resources/config/form_schema_builder.yaml', $configFile);
            }
        }
    }
}
