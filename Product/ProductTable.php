<?php
namespace Pionect\Backoffice\ContentElements\Tables\Product;

use Pionect\Backoffice\ContentElements\Tables\ElasticSearchTable;
use Pionect\Backoffice\ContentElements\Tables\Product\Columns\BrandColumn;
use Pionect\Backoffice\ContentElements\Tables\Product\Columns\FrontendCheckColumn;
use Pionect\Backoffice\ContentElements\Tables\Product\Columns\GroupColumn;
use Pionect\Backoffice\ContentElements\Tables\Product\Columns\ProductAttributeSetColumn;
use Pionect\Backoffice\ContentElements\Tables\Product\Filters\BrandFilter;
use Pionect\Backoffice\ContentElements\Tables\Product\Filters\ProductAttributeSetFilter;
use Pionect\Backoffice\Models\Product\Repositories\ProductRepository;

class ProductTable extends ElasticSearchTable
{
    /**
     * @var array
     */
    private $columnConfig = [
        [
            'name' => 'id',
            'default' => true
        ],
        [
            'name' => 'name',
            'default' => true
        ],
        [
            'name' => 'brand',
            'class' => BrandColumn::class
        ],
        [
            'name' => 'productattributeset_id',
            'default' => true,
            'class' => ProductAttributeSetColumn::class
        ],
        [
            'name' => 'group_id',
            'class' => GroupColumn::class
        ],
        [
            'name' => 'sku'
        ],
        [
            'name' => 'ean'
        ],
        [
            'name' => 'price'
        ],
        [
            'name' => 'cost_price'
        ],
        [
            'name' => 'type'
        ],
        [
            'name' => 'shipping_tier_id'
        ],
        [
            'name' => 'shipping_time_id'
        ],
        [
            'name' => 'variations',
            'sortable' => false,
            'default' => true
        ],
        [
            'name' => 'frontend_check',
            'sortable' => false,
            'default' => true,
            'cellTemplate' => 'backoffice::table.product.frontend_check_cell',
            'class' => FrontendCheckColumn::class
        ],
    ];

    /**
     * @var array
     */
    private $filterConfig = [
        [
            'name' => 'brand',
            'class' => BrandFilter::class,
        ],
        [
            'name' => 'productattributeset_id',
            'class' => ProductAttributeSetFilter::class,
        ],
        [
            'name' => 'group',
        ],
        [
            'name' => 'type',
        ],
        [
            'name' => 'shipping_tier_id',
        ],
        [
            'name' => 'shipping_time_id',
        ],
    ];

    /**
     * @var array
     */
    protected $searchFields = ['name', 'brand', 'ean'];

    /**
     * ProductTable constructor.
     * @param ProductRepository $productRepository
     * @param ProductTablePresenter $productTablePresenter
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductTablePresenter $productTablePresenter,
        ProductTableQueryCache $queryCache
    )
    {
        $this->repository = $productRepository;

        $this->tablePresenter = $productTablePresenter;

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
