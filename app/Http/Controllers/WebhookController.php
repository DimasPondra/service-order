<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    public function midtransNotification(Request $request)
    {
        try {
            DB::beginTransaction();

            Config::$serverKey = config('services.midtrans.serverKey');
            Config::$isProduction = config('services.midtrans.isProduction');
            Config::$isSanitized = config('services.midtrans.isSanitized');
            Config::$is3ds = config('services.midtrans.is3ds');

            $notification = new Notification();

            $status = $notification->transaction_status;
            $orderID = $notification->order_id;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status ?? null;

            $order = Order::where('code', $orderID)->first();

            if ($status == 'capture' && $paymentType == 'credit_card' && $fraudStatus == 'accept') {
                $order->status = 'paid';

                ClientHelper::giveCourseAccessToUser($order->course_id, $order->user_id);

            } elseif ($status == 'settlement') {
                $order->status = 'paid';

                ClientHelper::giveCourseAccessToUser($order->course_id, $order->user_id);

            } elseif ($status == 'pending') {
                $order->status = 'pending';
            } elseif ($status == 'deny' || $status == 'cancel' || $status == 'expire' || $status == 'failure') {
                $order->status = 'canceled';
            }

            $order->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
