<?php

namespace Letkode\FormSchemaBuilder\Model\Schema;

use Letkode\FormSchemaBuilder\Model\AbstractValuesModel;

class FormFieldBaseAttributeSchema extends AbstractValuesModel
{
    protected static array $values = [
        'default_value' => null,
        'set_options_values' => false,
        'create' => [
            'enabled' => true,
            'required' => true,
            'readonly' => false,
        ],
        'update' => [
            'enabled' => true,
            'required' => true,
            'readonly' => false,
        ],
        'update_multiple' => [
            'enabled' => true,
            'required' => false,
            'readonly' => false,
        ],
        'relationship_entity' => [
            'enabled' => false,
            'class' => null,
            'prop' => 'uuid',
        ],
        'unique' => [
            'enabled' => false,
            'entity' => null,
            'method' => null
        ],
        'filter' => [
            'enabled' => false,
            'key' => null,
        ],
        'check_by_role' => [
            'enabled' => false,
            'hierarchy' => true,
            'roles_allow' => []
        ],
        'header' => [
            'enabled' => false,
            'class' => '',
            'key' => null,
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
        'sql' => [
            'to_ignore_sql' => false,
            'alias_sql' => null,
            'name_sql' => null,
            'eval_filter' => null,
            'format_filter' => null,
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