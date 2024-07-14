<?php

namespace Letkode\FormSchemaBuilder\Model\Schema;

use Letkode\FormSchemaBuilder\Model\AbstractValuesModel;

class FormFieldBaseAttributeSchema extends AbstractValuesModel
{
    protected static array $values = [
        'create' => true,
        'edit' => true,
        'edit_multiple' => false,
        'readonly' => false,
        'required' => true,
        'ignore_required' => false,
        'default_value' => null,
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