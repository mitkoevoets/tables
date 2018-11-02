<?php

namespace Pionect\Backoffice\ContentElements\Tables;


use Pionect\Helpers\PriceHelper;

abstract class TablePresenter
{
    protected $priceHelper;

    protected $queryCache;

    public function __construct(PriceHelper $priceHelper)
    {
        $this->priceHelper = $priceHelper;

    }

    /**
     * @param $queryCache
     */
    public function setQueryCache($queryCache): void
    {
        $this->queryCache = $queryCache;
    }

    /**
     * @param $item
     * @param $property
     * @return mixed
     */
    public function getProperty($item, $property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}($item);
        }

        return $item->{$property};
    }
}
