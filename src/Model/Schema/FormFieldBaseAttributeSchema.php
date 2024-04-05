<?php

namespace Letkode\FormSchemaBuilder\Model\Schema;

use Letkode\FormSchemaBuilder\Model\AbstractValuesModel;

class FormFieldBaseAttributeSchema extends AbstractValuesModel
{
    protected static array $values = [
        'create' => true, // Mostrar en la tabla.
        'edit' => true, // Se edita en el formulario.
        'edit_multiple' => false,
        'readonly' => false, // Solo lectura en el formulario.
        'required' => true, // Requerido en el formulario.
        'prop_relation' => null,
        'entity_relation' => null,
        'unique' => false, // Valor único para ser procesado.
        'unique_params' => [
            'entity' => null,
            'method' => null
        ],
        'sql' => [ //  Parámetros para búsqueda en SQL
            'to_ignore_sql' => false,
            'alias_sql' => null,
            'name_sql' => null,
            'eval_filter' => null,
            'format_filter' => null,
        ],
        'filter' => [
            'enabled' => false,
        ],
        'is_relationship_entity' => null,
        'check_by_role' => ['enabled' => false, 'hierarchy' => true, 'roles_allow' => []],
        'header' => [
            'enabled' => false,
            'class' => '',
            'key_show' => null,
            'position' => 0,
            'link_action' => [
                'icon' => null,
                'type' => null,
                'enabled' => false,
                'route' => null,
                'style' => null,
                'route_params' => [],
                'children' => [],
            ]
        ],
        'bulk_upload' => [
            'enabled' => false,
            'rename' => null,
            'check_tag_options' => null,
            'ignore_check_invalid' => false,
            'show_summary' => true
        ],
    ];
}