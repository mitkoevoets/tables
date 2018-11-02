<?php

namespace Pionect\Backoffice\ContentElements\Tables\Lead;


use Pionect\Backoffice\ContentElements\Tables\TablePresenter;
use Pionect\Backoffice\Models\OfferRequest\OfferRequest;
use Pionect\Helpers\PriceHelper;

class LeadTablePresenter extends TablePresenter
{
    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    public function __construct()
    {
        $this->priceHelper = new PriceHelper();
    }

    /**
     * @param OfferRequest $item
     * @return string
     */
    public function name(OfferRequest $item)
    {
        return $item->present()->name();
    }

    /**
     * @param OfferRequest $item
     * @return string
     */
    public function total(OfferRequest $item)
    {
        return '&euro;' . $this->priceHelper->formatPrice($item->getTotal());
    }

    /**
     * @param OfferRequest $item
     * @return string
     */
    public function date(OfferRequest $item)
    {
        return $item->sent_at->format('l j F Y');
    }

    /**
     * @param OfferRequest $item
     * @return string
     */
    public function status(OfferRequest $item)
    {
        return ucfirst($item->state);
    }
}