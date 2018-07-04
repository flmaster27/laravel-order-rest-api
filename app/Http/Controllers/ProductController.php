<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Order;

class ProductController extends Controller
{
    /**
     * Добавляем товар к заказу
     *
     * @param Request $request
     * @param $oid
     * @return mixed
     */
    public function add(Request $request, $orderId)
    {
        return $request->user()->orders()->find($orderId)->products()->create([
            'order_id' => $orderId
        ]);
    }

    /**
     * Получаем список товаров пользователя из оплаченных заказов
     *
     * @param Request $request
     * @param $orderId
     * @return mixed
     */

    public function paid(Request $request)
    {
        return $request->user()->orders()->where('order_status_id', Order::PAID)->with('products')->get()->pluck('products')->flatten();
    }
}
