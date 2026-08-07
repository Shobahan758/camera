<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\IncompleteOrder;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $quantity = (int) $data['quantity'];
        $unitPrice = 13990;
        $matchingOrderCount = Order::query()
            ->where('phone', $data['phone'])
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($data['name'])])
            ->count();
        $status = $matchingOrderCount >= 2 ? 'fake' : 'pending';

        $order = Order::create([
            ...$data,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $unitPrice * $quantity,
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
        ]);

        if ($request->filled('incomplete_token')) {
            IncompleteOrder::where('token', $request->string('incomplete_token'))->delete();
        }

        $message = 'ধন্যবাদ! আপনার অর্ডারটি গ্রহণ করা হয়েছে।';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'order_id' => $order->id,
                'total' => $order->total,
            ], 201);
        }

        return back()->with('order_success', $message);
    }
}
