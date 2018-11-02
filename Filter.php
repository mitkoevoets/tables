<?php

namespace Pionect\Backoffice\ContentElements\Tables;

use Pionect\PertwoRepositories\Product\Search\Elasticsearch\Facet;
use Pionect\PertwoRepositories\Product\Search\FacetValue;

abstract class Filter implements TableFilterContract
{
    /**
     * @var array
     */
    public $config;

    /**
     * @var array
     */
    public $selectedFilters;

    /**
     * @var Facet
     */
    public $facet;

    /**
     * @var QueryCache
     */
    protected $tableQueryCache;

    /**
     * Filter constructor.
     * @param array $config
     * @param array $facets
     * @param mixed $selectedFilters
     */
    public function __construct(array $config, array $facets, $selectedFilters, QueryCache $tableQueryCache)
    {
        $this->config = $config;

        $this->facet = $this->findFacet($facets);

        $this->selectedFilters = is_array($selectedFilters) ? $selectedFilters : [];

        $this->tableQueryCache = $tableQueryCache;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->config['name'];
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getDescription()
    {
        return trans('backoffice::table.' . $this->transKey());
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];

        if($this->facet !== null){

            /**
             * @var FacetValue[] $facetValues
             */
            $facetValues = $this->facet->getValues();

            foreach($facetValues as $facetValue){
                $value['name'] = $this->getFilterName($facetValue);

                $value['key'] = $this->getFilterKey($facetValue);

                $value['isChecked'] = $this->isChecked($value['key'], $this->facet->getName());

                $values[] = $value;
            }

        }
        return $values;
    }

    /**
     * @param FacetValue $facetValue
     * @return string
     */
    protected function getFilterName(FacetValue $facetValue)
    {
        return $facetValue->getValue();
    }

    /**
     * @param $key
     * @return bool
     */
    protected function isChecked($key, $facetName)
    {
        if(array_key_exists($facetName, $this->selectedFilters)) {
            $filterSelectedValues = $this->selectedFilters[$facetName];

            return (bool)in_array($key, $filterSelectedValues);
        }

        return false;
    }
    /**
     * @param FacetValue $facetValue
     * @return string
     */
    protected function getFilterKey(FacetValue $facetValue)
    {
        return $facetValue->getValue();
    }

    /**
     * @return string
     */
    protected function transKey()
    {
        return $this->getName();
    }

    /**
     * @param Facet[] $facets
     * @return null|Facet
     */
    protected function findFacet($facets)
    {
        foreach($facets as $facet)
        {
            if($facet->getName() === $this->config['name'])
            {
                return $facet;
            }
        }

        return null;
    }
}
