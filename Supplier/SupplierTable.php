<?php

namespace Pionect\Backoffice\ContentElements\Tables\Supplier;

use Illuminate\Pagination\LengthAwarePaginator;
use Pionect\Backoffice\ContentElements\Tables\ElasticSearchTable;
use Pionect\Backoffice\ContentElements\Tables\EloquentTable;
use Pionect\Backoffice\ContentElements\Tables\QueryCache;
use Pionect\Backoffice\Models\Supplier\Repositories\SupplierRepository;
use Pionect\Backoffice\Models\Supplier\Supplier;

class SupplierTable extends ElasticSearchTable
{
    private $columnConfig = [
        [
            'name' => 'name',
            'default' => true
        ],
        [
            'name' => 'telephone',
            'default' => true
        ],
        [
            'name' => 'email',
            'default' => true
        ],
        [
            'name' => 'city',
            'default' => true
        ],
        [
            'name' => 'address',
            'default' => true
        ],
        [
            'name' => 'postalcode',
            'default' => true
        ],
        [
            'name' => 'id'
        ],
        [
            'name' => 'dropship'
        ],
    ];

    /**
     * @var array
     */
    private $filterConfig = [
        [
            'name' => 'city',
        ],
        [
            'name' => 'dropship',
        ],
    ];


    /**
     * @var array
     */
    protected $searchFields = ['city', 'dropship'];

    /**
     * SupplierTable constructor.
     * @param SupplierRepository $supplierRepository
     * @param QueryCache $queryCache
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        QueryCache $queryCache
    )
    {
        $this->repository = $supplierRepository;

        return parent::__construct($queryCache);
    }

    /**
     * @return array
     */
    protected function getColumnConfig()
    {
        return $this->columnConfig;
    }

    /**
     * @return array
     */
    protected function getFilterConfig()
    {
        return $this->filterConfig;
    }
}
