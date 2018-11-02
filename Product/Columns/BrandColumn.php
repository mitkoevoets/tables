<?php

namespace Pionect\Backoffice\ContentElements\Tables\Product\Columns;

use Pionect\Backoffice\ContentElements\Tables\Column;

class BrandColumn extends Column
{
    /**
     * @return string
     */
    public function transKey()
    {
        return 'brand';
    }
}