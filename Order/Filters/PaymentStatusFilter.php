<?php
namespace Pionect\Backoffice\ContentElements\Tables\Order\Filters;

use Pionect\Backoffice\ContentElements\Tables\ElasticSearchFilter;
use Pionect\PertwoRepositories\Product\Search\FacetValue;

class PaymentStatusFilter extends ElasticSearchFilter
{
    /**
     * @param FacetValue $facetValue
     * @return mixed
     */
    protected function getFilterName(FacetValue $facetValue)
    {
        return strtoupper($facetValue->getValue());
    }
}
