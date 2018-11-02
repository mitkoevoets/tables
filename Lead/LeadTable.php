<?php
namespace Pionect\Backoffice\ContentElements\Tables\Lead;

use Pionect\Backoffice\ContentElements\Tables\ElasticSearchTable;
use Pionect\Backoffice\ContentElements\Tables\Lead\Columns\DateColumn;
use Pionect\Backoffice\ContentElements\Tables\Lead\Columns\NameColumn;
use Pionect\Backoffice\Models\OfferRequest\Repositories\OfferRequestRepository;

class LeadTable extends ElasticSearchTable
{
    private $columnConfig = [
        [
            'name' => 'name',
            'class' => NameColumn::class
        ],
        [
            'name' => 'email'
        ],
        [
            'name' => 'total',
            'sortable' => false
        ],
        [
            'name' => 'date',
            'class' => DateColumn::class
        ],
        [
            'name' => 'status'
        ],
    ];

    public function __construct(OfferRequestRepository $offerRequestRepository, LeadTablePresenter $leadTablePresenter)
    {
        $this->repository = $offerRequestRepository;

        $this->tablePresenter = $leadTablePresenter;

        return parent::__construct();
    }

    protected function getColumnConfig()
    {
        return $this->columnConfig;
    }

}