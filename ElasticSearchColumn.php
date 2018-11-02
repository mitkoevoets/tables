<?php

namespace Pionect\Backoffice\ContentElements\Tables;


class ElasticSearchColumn extends Column
{
    public function __construct(array $config, $queryParams)
    {
        parent::__construct($config, $queryParams);
    }
}