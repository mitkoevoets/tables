<?php

namespace Pionect\Backoffice\ContentElements\Tables\Product\Filters;

use Pionect\Backoffice\ContentElements\Tables\ElasticSearchFilter;
use Pionect\Backoffice\ContentElements\Tables\Product\ProductTableQueryCache;
use Pionect\PertwoRepositories\Product\Search\FacetValue;

class ProductAttributeSetFilter extends ElasticSearchFilter
{
    /**
     * @return string
     */
    public function transKey()
    {
        return 'product_attributeset';
    }
    
    /**
     * @param FacetValue $facetValue
     * @return mixed
     */
    protected function getFilterName(FacetValue $facetValue)
    {
        /**
         * @var ProductTableQueryCache $tableQueryCache
         */
        $tableQueryCache = $this->tableQueryCache;

        return $tableQueryCache->productAttributeSets->where('id', $facetValue->getValue())->first()->name;
    }

}
