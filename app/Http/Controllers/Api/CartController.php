<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{

    // Add item to cart
    public function addToCart(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
        ]);
    }

    // View cart items
    public function viewCart()
    {
        $user = Auth::user();

        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cartItems,
        ]);
    }

    // Remove item from cart
    public function removeFromCart($id)
    {
        $user = Auth::user();

        $cartItem = Cart::where('user_id', $user->id)->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.',
        ]);
    }



    public function updateCartItem(Request $request, $id)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Temukan item keranjang berdasarkan ID dan pengguna
        $cartItem = Cart::where('id', $id)->where('user_id', $user->id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        // Perbarui kuantitas item
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully.',
            'data' => $cartItem,
        ]);
    }
}
