<?php namespace Pionect\Backoffice\ContentElements\Tables\Order;

use Pionect\Backoffice\ContentElements\Tables\ElasticSearchTable;
use Pionect\Backoffice\ContentElements\Tables\Order\Filters\PaymentStatusFilter;
use Pionect\Backoffice\ContentElements\Tables\Order\OrderTableQueryCache;
use Pionect\Backoffice\Models\Order\Order;
use Pionect\Backoffice\Models\Order\Repositories\OrderRepository;

class OrderTable extends ElasticSearchTable
{
    /**
     * @var string
     */
    public $primaryKey = 'orderid';

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
            'name' => 'date',
            'default' => true
        ],
        [
            'name' => 'order_status',
            'default' => true
        ],
        [
            'name' => 'total_price',
            'default' => true
        ],
        [
            'name' => 'payment_status',
            'default' => true,
        ],
        [
            'name' => 'margin',
            'default' => true,
        ],
        [
            'name' => 'shop',
            'default' => true
        ],
        [
            'name' => 'tags',
            'default' => true,
            'sortable' => false,
        ],
        [
            'name' => 'customer_group',
        ],
        [
            'name' => 'city',
        ],
        [
            'name' => 'country_code',
        ],
        [
            'name' => 'contact_type',
        ],
    ];

    /**
     * @var array
     */
    protected $searchFields = ['name', 'country_code','city'];

    /**
     * @var array
     */
    private $filterConfig = [
        [
            'name' => 'order_status',
        ],
        [
            'name' => 'payment_status',
            'class' => PaymentStatusFilter::class,
        ],
        [
            'name' => 'tags',
        ],
        [
            'name' => 'customer_group',
        ],
        [
            'name' => 'contact_type',
        ],
    ];

    /**
     * OrderTable constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderTablePresenter $orderTablePresenter,
        OrderTableQueryCache $queryCache
    )
    {
        $this->repository = $orderRepository;

        $this->tablePresenter = $orderTablePresenter;

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

    /**
     * @param null|Order $item
     * @return string
     */
    public function getRowStylingClass($item = null)
    {
        if($item->in_stock) {
            return 'in-stock';
        }

        return '';
    }
}
