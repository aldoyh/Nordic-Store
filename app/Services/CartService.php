<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getOrCreateCart(Shop $shop): Cart
    {
        $user = auth()->user();

        if ($user) {
            $cart = $shop->carts()->where('user_id', $user->id)->first();
            if ($cart) {
                return $cart;
            }

            $sessionId = Session::getId();
            $guestCart = $shop->carts()->where('session_id', $sessionId)->first();

            if ($guestCart) {
                $guestCart->update(['user_id' => $user->id, 'session_id' => null]);
                return $guestCart;
            }

            return $shop->carts()->create([
                'user_id' => $user->id,
                'total_price' => 0,
            ]);
        }

        $sessionId = Session::getId();
        return $shop->carts()->firstOrCreate(
            ['shop_id' => $shop->id, 'session_id' => $sessionId],
            ['total_price' => 0]
        );
    }

    public function addToCart(Shop $shop, Product $product, int $quantity = 1): CartItem
    {
        $cart = $this->getOrCreateCart($shop);

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
                'subtotal' => ($existingItem->quantity + $quantity) * $product->price,
            ]);
            return $existingItem;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'subtotal' => $quantity * $product->price,
        ]);
    }

    public function removeFromCart(CartItem $item): void
    {
        $item->delete();
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            $this->removeFromCart($item);
            return $item;
        }

        $item->update([
            'quantity' => $quantity,
            'subtotal' => $quantity * $item->unit_price,
        ]);

        return $item;
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->delete();
    }

    public function getCartTotal(Cart $cart): float
    {
        return (float) $cart->items()->sum('subtotal');
    }
}
