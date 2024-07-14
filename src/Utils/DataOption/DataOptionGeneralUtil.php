<?php

namespace Letkode\FormSchemaBuilder\Utils\DataOption;

use Letkode\FormSchemaBuilder\Entity\FormOptionGeneral;
use Letkode\FormSchemaBuilder\Interface\DataOptionInterface;
use Letkode\FormSchemaBuilder\Utils\DataOptionUtil;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DataOptionGeneralUtil extends DataOptionUtil implements DataOptionInterface
{

    public function get(array $paramsAttr = []): array
    {
        $setOption = $paramsAttr['set'] ??  throw new BadRequestException(
            'Options undefined in field parameters'
        );

        if (null === $formOptionGeneral = $this->getEntityManager()
                ->getRepository(FormOptionGeneral::class)
                ->findOneBy(['tag' => $setOption])
        ) {
            throw new BadRequestException(
                sprintf(
                    'Set options "%s" does not have set options',
                    $setOption
                )
            );
        }

        $parameters = [
            '_key' => $paramsAttr['id_column'] ?? 'value',
            '_text' => $paramsAttr['text_column'] ?? 'text',
            '_with_not_apply' => $paramsAttr['with_not_apply'] ?? false,
            '_with_all_option' => $paramsAttr['with_all_option'] ?? false,
            '_all_option_params' => $paramsAttr['all_option_params'] ??
                ['id' => -1, 'text' => 'Todas las Opciones'],
        ];

        return $formOptionGeneral->getValuesToArray($parameters);
    }

}