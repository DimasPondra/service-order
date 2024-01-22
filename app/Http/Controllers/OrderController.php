<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $customCode = "ORD-" . rand(0,9999) . "-SO-" . date('dHis');

            $request->merge([
                'price' => floatval($course['data']['price']),
                'code' => $customCode,
            ]);

            $data = $request->only(['price', 'code', 'user_id', 'course_id']);

            $order = new Order();
            $this->orderRepository->save($order->fill($data));

            /** proses simpan ke midtrans dan mendapatkan payment url */

            /** update data order dan simpan payment url dari midtrans */

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
            'message' => 'Order successfully created.'
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
