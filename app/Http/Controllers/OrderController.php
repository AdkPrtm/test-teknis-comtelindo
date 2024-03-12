<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function show(): View
    {
        $userId = auth()->id();
        return view('order', ['orders' => Order::where('user_id', $userId)]);
    }

    public function showByIdOrder($orderId): View
    {
        $userId = auth()->id();

        $result = Order::with('userData', 'productData')
            ->where('user_id', $userId)
            ->where('id', (int)$orderId)
            ->orderBy('id', 'desc')
            ->get();

        return view('order', ['orders' => $result]);
    }

    public function store(Request $request): View
    {
        $data = $request->only('product_id', 'status', 'quantity');

        $validator = Validator::make($data, [
            'product_id' => 'required|integer',
            'status' => 'required|string',
            'quantity' => 'required|integer',
        ]);
        $userId = auth()->id();
        $orderCode = strtoupper(Str::random(15));

        if ($validator->fails()) {
            return view('order', ['orders' => Order::where('user_id', $userId)])->withErrors($validator);
        }

        DB::beginTransaction();

        Order::create([
            'product_id' => $request->product_id,
            'user_id' => $userId,
            'order_code' => $orderCode,
            'status' => $request->status,
            'quantity' => $request->quantity,
        ]);

        $product = Product::findOrFail($request->product_id);
        $newStock = $product->stock - $request->quantity;
        if ($newStock < 0) {
            DB::rollback();
            return view('order', ['orders' => Order::where('user_id', $userId)])->with('message', 'Out of stock!');
        }
        $product->update(['stock' => $newStock]);
        DB::commit();
        return view('order', ['orders' => Order::where('user_id', $userId)]);
    }
}
