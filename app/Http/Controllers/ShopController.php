<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function show(Shop $shop): View
    {
        if (!$shop->is_active) {
            abort(404);
        }

        $products = $shop->products()->where('is_available', true)->paginate(12);

        $cart = $shop->carts()
            ->where(function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', session()->getId());
                }
            })
            ->first();

        return view('shop.show', compact('shop', 'products', 'cart'));
    }
}
