<?php
namespace Pionect\Backoffice\ContentElements\Tables\Product\Columns;

use Pionect\Backoffice\ContentElements\Tables\Column;

class ProductAttributeSetColumn extends Column
{
    /**
     * @return string
     */
    public function transKey()
    {
        return 'product_attributeset';
    }
}