<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function show(Shop $shop): View
    {
        $cart = $this->cartService->getOrCreateCart($shop);
        $items = $cart->items()->with('product')->get();

        return view('cart.show', compact('shop', 'cart', 'items'));
    }

    public function add(Request $request, Shop $shop, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $this->cartService->addToCart($shop, $product, $validated['quantity']);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, Shop $shop, CartItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:100',
        ]);

        if ($validated['quantity'] === 0) {
            $this->cartService->removeFromCart($item);
        } else {
            $this->cartService->updateQuantity($item, $validated['quantity']);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(Shop $shop, CartItem $item): RedirectResponse
    {
        $this->cartService->removeFromCart($item);

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear(Shop $shop): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart($shop);
        $this->cartService->clearCart($cart);

        return redirect()->route('shop.show', $shop)->with('success', 'Cart cleared!');
    }
}
