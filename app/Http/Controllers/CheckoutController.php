<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function shipping(Shop $shop): View
    {
        $cart = $this->cartService->getOrCreateCart($shop);
        if ($cart->items()->count() === 0) {
            return redirect()->route('shop.show', $shop)->with('error', 'Your cart is empty!');
        }

        $items = $cart->items()->with('product')->get();
        return view('checkout.shipping', compact('shop', 'cart', 'items'));
    }

    public function storeShipping(Request $request, Shop $shop): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email:rfc,dns',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $cart = $this->cartService->getOrCreateCart($shop);
        $deliveryAddress = "{$validated['address_line1']}, " .
            ($validated['address_line2'] ? "{$validated['address_line2']}, " : "") .
            "{$validated['city']}, {$validated['postcode']}, {$validated['country']}";

        $order = Order::create([
            'shop_id' => $shop->id,
            'user_id' => auth()->id(),
            'total_price' => $cart->total_price,
            'status' => 'pending',
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'delivery_address' => $deliveryAddress,
        ]);

        foreach ($cart->items()->with('product')->get() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_title' => $item->product->title,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
            ]);
        }

        session(['order_id' => $order->id, 'shop_id' => $shop->id]);

        return redirect()->route('checkout.payment', ['shop' => $shop->instagram_username]);
    }

    public function payment(Shop $shop): View
    {
        $orderId = session('order_id');
        if (!$orderId) {
            return redirect()->route('shop.show', $shop);
        }

        $order = Order::findOrFail($orderId);

        return view('checkout.payment', compact('shop', 'order'));
    }

    public function storePayment(Request $request, Shop $shop): RedirectResponse
    {
        $orderId = session('order_id');
        $order = Order::findOrFail($orderId);

        $order->update([
            'status' => 'paid',
            'payment_method' => 'stripe',
            'payment_id' => 'test_' . uniqid(),
        ]);

        $cart = $this->cartService->getOrCreateCart($shop);
        $this->cartService->clearCart($cart);

        session()->forget(['order_id', 'shop_id']);

        return redirect()->route('checkout.confirmation', ['shop' => $shop->instagram_username, 'order' => $order->id])
            ->with('success', 'Payment successful!');
    }

    public function confirmation(Shop $shop, Order $order): View
    {
        $items = $order->items()->with('product')->get();

        return view('checkout.confirmation', compact('shop', 'order', 'items'));
    }
}
