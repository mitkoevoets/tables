<?php

namespace Pionect\Backoffice\ContentElements\Tables\Product\Columns;


use Pionect\Backoffice\ContentElements\Tables\Column;

class FrontendCheckColumn extends Column
{
    /**
     * @param $entity
     * @return mixed
     */
    public function getExportValue($entity)
    {
        $fieldName = $this->fieldName;

        $frontendCheck = $entity->$fieldName;

        if(!is_array($frontendCheck)){
            return '';
        }

        return ($frontendCheck[0] && $frontendCheck[1]) ? 'true' : 'false';
    }
}
