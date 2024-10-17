<?php

namespace Letkode\FormSchemaBuilder\Utils\DataOption;

use Letkode\FormSchemaBuilder\Interface\DataOptionInterface;
use Letkode\FormSchemaBuilder\Utils\DataOptionUtil;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DataOptionEntityUtil extends DataOptionUtil implements DataOptionInterface
{
    public function get(array $paramsAttr = []): array
    {
        $method = $paramsAttr['method'] ?? 'getDataEntity';

        if (!method_exists(
            sprintf('%s\%s', $this->getConfig('namespace_entity', 'App\Entity'), $paramsAttr['entity']),
            $method
        )) {
            throw new BadRequestException(
                sprintf('Method "%s" not found in entity class', $method)
            );
        }

        $parameters = [
            '_key' => $paramsAttr['id_column'] ?? 'uuid',
            '_text' => $paramsAttr['text_column'] ?? 'name',
            '_with_all_option' => $paramsAttr['with_all_option'] ?? false,
            '_with_not_apply' => $paramsAttr['with_not_apply'] ?? false
        ];

        return $this->getEntityManager()
            ->getRepository(
                sprintf(
                    '%s\%s',
                    $this->getConfig('namespace_entity', 'App\Entity'),
                    $paramsAttr['entity']
                )
            )
            ->{$method}($parameters);
    }
}