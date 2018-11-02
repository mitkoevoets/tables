<?php
/**
 * Created by PhpStorm.
 * User: egbertwietses
 * Date: 28-09-17
 * Time: 16:32
 */

namespace Pionect\Backoffice\ContentElements\Tables;


trait TableRequestParameters
{


    protected function perPage()
    {
        return request()->get($this->tableKey() . 'PerPage');
    }

    protected function from()
    {
        return request()->get($this->tableKey() . 'From');
    }

    protected function currentPage()
    {
        return request()->get($this->tableKey() . 'CurrentPage');
    }

    protected function sort()
    {
        return request()->get($this->tableKey() . 'Sort');
    }

    protected function sortDirection()
    {
        return request()->get($this->tableKey() . 'SortDirection');
    }

    protected function search()
    {
        return request()->get($this->tableKey() . 'Search');
    }

    protected function tableKey()
    {
        return self::class;
    }

}