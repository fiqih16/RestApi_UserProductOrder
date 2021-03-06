<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\products_user;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $productId = $request->productId;

        $lastProduct = $user->products()->orderBy('order_id', 'DESC')->first();
        $orderId = 1;
        if($lastProduct)
        {
            if($lastProduct->pivot->status == 'cart')
            {
                $orderId = $lastProduct->pivot->order_id;
            }
            else
            {
                $orderId = $lastProduct->pivot->order_id + 1;
            }
        }

        $price = Product::find($productId)->harga_product;
        $today = Carbon::now();

        $user->products()->attach($productId, [
            'order_id' => $orderId,
            'harga_product' => $price,
            'quantity' => $request->quantity,
            'created_at' => $today,
            'status' => 'cart'
        ]);

        return response(['status' => true]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $productId = $request->productId;

        $user->products()->wherePivot('status', 'cart')->detach($productId);

        return response(['status' => true]);
    }

    public function cart(Request $request)
    {
        $user = $request->user();

        $cart = $user->products()->wherePivot('status', 'cart')->get();
        $cart->load('category');
        return response(['data' => $cart]);
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $cart = $user->products()
              ->wherePivot('status', '!=', 'cart')
              ->orderBy('checkout_at', 'DESC')->get();
        $cart->load('category');
        return response(['data' => $cart]);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        $cart = $user->products()->wherePivot('status', 'cart')->get();
        foreach($cart as $item)
        {
            $user->products()
                 ->wherePivot('status', 'cart')
                 ->updateExistingPivot($item->id, [
                    'status' => 'checkout',
                    'checkout_at' => Carbon::now()
                 ]);
        }
        return response(['status' => true]);
    }
}
