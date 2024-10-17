<?php

namespace Letkode\FormSchemaBuilder;

class Installer
{
    public static function install(): void
    {
        $currentWorkingDir = getcwd();
        $configDir = realpath($currentWorkingDir . '/config/packages');

        if ($configDir === false) {
            // Si el directorio no existe, crearlo
            $configDir = $currentWorkingDir . '/config/packages';
            mkdir($configDir, 0755, true);
        }

        $configFile = $configDir . '/form_schema_builder.yaml';

        if (!file_exists($configFile)) {
            if (!copy(__DIR__ . '/../Resources/config/form_schema_builder.yaml', $configFile)) {
                throw new \RuntimeException('Error al copiar el archivo de configuración.');
            }
        }
    }
}
