<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    private $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function get()
    {
        $orders = $this->model;

        return $orders->get();
    }

    public function save(Order $order)
    {
        $order->save();

        return $order;
    }
}
