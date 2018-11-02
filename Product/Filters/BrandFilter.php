<?php

namespace Pionect\Backoffice\ContentElements\Tables\Product\Filters;

use Pionect\Backoffice\ContentElements\Tables\ElasticSearchFilter;
use Illuminate\Support\Facades\DB;
use Pionect\Backoffice\ContentElements\Tables\Product\ProductTableQueryCache;
use Pionect\PertwoRepositories\Product\Search\FacetValue;

class BrandFilter extends ElasticSearchFilter
{
    /**
     * @return string
     */
    public function transKey()
    {
        return 'brand';
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

        return $tableQueryCache->brands->where('id', $facetValue->getValue())->first()->name;
    }

}
