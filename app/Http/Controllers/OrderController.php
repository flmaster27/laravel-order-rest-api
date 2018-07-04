<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderStatus;
use App\Order;
use App\OrderLog;
use App\User;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Создаем новый заказ
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $createdOrder = $request->user()->orders()->create([
            'order_status_id' => Order::NEW_ORDER
        ]);

        //LOG
        if ($createdOrder) {
            OrderLog::create([
                'order_status_id' => $createdOrder->order_status_id,
                'order_id' => $createdOrder->id
            ]);
        }

        return $createdOrder;
    }

    /**
     * Смена статуса заказа. В идеале - статус должен меняться системой, но в нашем
     * тестовом случае предположим, что его должен менять сам пользователь. Проверяем
     * наличие статуса в таблице. Если он есть, то уже меняем его в базе при условии, что
     * заказ существует, он принадлежит текущему пользователю и статус не был в
     * состоянии "Оплачен"
     *
     * @param Request $request
     * @param $orderId
     * @param $statusId
     * @return mixed
     */
    public function change(Request $request, $orderId, $statusId)
    {
        /**
         * status check
         * В оригинале проверяли по базе, но проверим по валидатору
         */
        $validator = Validator::make(
            ['status' => $statusId],
            ['status' => 'required|integer|between:' . Order::NEW_ORDER . ',' . Order::PAID]
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        //А теперь все таки по базе
        $status = OrderStatus::findOrFail($statusId);

        $changedOrder = $request->user()->orders()->where([
            ['id', '=', $orderId],
            ['order_status_id', '<>', Order::PAID]
        ])->update([
            'order_status_id' => $status->id
        ]);

        //LOG
        if ($changedOrder) {

            if ($status->id == Order::PAID) {
                $request->user()->notify(new InvoicePaid());
            }

            OrderLog::create([
                'order_status_id' => $status->id,
                'order_id' => $orderId
            ]);
        }


        return $changedOrder;


    }
}