<?php

namespace Pionect\Backoffice\ContentElements\Tables\Order;

use Pionect\Backoffice\ContentElements\Tables\TablePresenter;
use Pionect\Backoffice\Models\Order\Order;
use Pionect\Backoffice\Models\Payment\Payment;

class OrderTablePresenter extends TablePresenter
{
    public function date(Order $order)
    {
        return ucfirst(strftime('%A %e %h %H:%M', $order->date->timestamp));
    }

    public function order_status(Order $order)
    {
        if (!empty($order->order_status) && is_string($order->order_status)) {

            return trans('backoffice::order.states.' . $order->order_status);
        }

        return '';
    }

    public function total_price(Order $order)
    {
        return '&euro; ' . $this->priceHelper->formatPrice($order->total_price);
    }

    public function payment_status(Order $order)
    {
        return $order->payment_status;
    }

    public function margin(Order $order)
    {
        if(!empty($order->margin)){
            if ($order->margin < 21) {
                return '<span style="color:darkred">' . $order->margin . '&percnt;</span>';
            } else {
                return $order->margin . '&percnt;';
            }
        }

        return 'n/a';
    }

    public function tags(Order $order)
    {
        $tags = $this->queryCache->tags->where('taggable_id', $order->id);

        return $tags;
    }
}
