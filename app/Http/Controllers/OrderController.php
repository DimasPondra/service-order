<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $course = ClientHelper::getCourseByID($request->course_id);

            if ($course['data']['status'] !== 'published') {
                return response()->json([
                    'status' => 'error',
                    'message' => "Can't take this course, because it hasn't been released yet."
                ], 400);
            }

            $customCode = "ORD-" . rand(0,9999) . "-SO-" . date('dHis');

            $request->merge([
                'price' => floatval($course['data']['price']),
                'code' => $customCode,
            ]);

            /** proses simpan ke midtrans dan mendapatkan payment url */
            Config::$serverKey = config('services.midtrans.serverKey');
            Config::$isProduction = config('services.midtrans.isProduction');
            Config::$isSanitized = config('services.midtrans.isSanitized');
            Config::$is3ds = config('services.midtrans.is3ds');

            $midtrans = [
                'transaction_details' => [
                    'order_id' => $customCode,
                    'gross_amount' => $request->price,
                ],
            ];

            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            /** update data order dan simpan payment url dari midtrans */
            $request->merge(['payment_url' => $paymentUrl]);

            $data = $request->only(['price', 'code', 'payment_url', 'user_id', 'course_id']);

            $order = new Order();
            $order = $this->orderRepository->save($order->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order successfully created.',
            'data' => [
                'payment_url' => $paymentUrl
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
