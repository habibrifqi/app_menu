<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_no' => 'required|string|max:5',
            // 'status' => 'required|string|max:100',
            'waiters_id' => 'required|integer|exists:users,id',
            // 'cashiers_id' => 'nullable|integer|exists:users,id',
            // 'items' => 'required|array|min:1',
            // 'items.*.item_id' => 'required|integer|exists:items,id',
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $orderItems = [];

            // foreach ($validated['items'] as $orderItem) {
            //     $item = Item::find($orderItem['item_id']);

            //     if (!$item) {
            //         throw new \Exception("Item with ID {$orderItem['item_id']} not found");
            //     }

            //     $total += $item->price;
            //     $orderItems[] = [
            //         'item_id' => $item->id,
            //         'price' => $item->price,
            //     ];
            // }

            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'table_no' => $validated['table_no'],
                'order_date' => now()->toDateString(),
                'ordertime' => now()->toTimeString(),
                'status' => 'ordering',
                // 'total' => $total,
                'total' => '0',
                'waiters_id' => $validated['waiters_id'],
                // 'cashiers_id' => $validated['cashiers_id'] ?? null,
            ]);

            // foreach ($orderItems as $orderItem) {
            //     OrderDetail::create([
            //         'order_id' => $order->id,
            //         'item_id' => $orderItem['item_id'],
            //         'price' => $orderItem['price'],
            //     ]);
            // }

            DB::commit();

            // $order->load(['orderDetails.item', 'waiter', 'cashier']);

            return response()->json('oke');

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Order created successfully',
            //     'data' => [
            //         'id' => $order->id,
            //         'customer_name' => $order->customer_name,
            //         'table_no' => $order->table_no,
            //         'order_date' => $order->order_date,
            //         'ordertime' => $order->ordertime,
            //         'status' => $order->status,
            //         'total' => $order->total,
            //         'waiter' => [
            //             'id' => $order->waiter->id,
            //             'name' => $order->waiter->name,
            //         ],
            //         'cashier' => $order->cashier ? [
            //             'id' => $order->cashier->id,
            //             'name' => $order->cashier->name,
            //         ] : null,
            //         'items' => $order->orderDetails->map(function ($detail) {
            //             return [
            //                 'id' => $detail->id,
            //                 'item_id' => $detail->item_id,
            //                 'item_name' => $detail->item->name,
            //                 'price' => $detail->price,
            //             ];
            //         }),
            //     ]
            // ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
