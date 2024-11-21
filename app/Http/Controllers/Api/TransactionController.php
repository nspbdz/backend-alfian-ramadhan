<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Buat transaksi dari keranjang
    public function createTransaction(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        $discount = $totalPrice > 50000 ? $totalPrice * 0.10 : 0;
        $shippingFee = $totalPrice > 15000 ? 0 : 5000;
        $finalPrice = $totalPrice - $discount + $shippingFee;

        // Buat transaksi
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'total_price' => $totalPrice,
            'discount' => $discount,
            'shipping_fee' => $shippingFee,
            'final_price' => $finalPrice,
        ]);

        // Pindahkan item dari keranjang ke detail transaksi
        foreach ($cartItems as $cartItem) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);

            $cartItem->delete(); // Hapus item dari keranjang
        }

        return response()->json(['message' => 'Transaction successful', 'transaction' => $transaction], 200);
    }

    // Lihat transaksi
    public function viewTransactions()
    {
        $transactions = Transaction::with('details.product')->where('user_id', Auth::id())->get();

        return response()->json(['transactions' => $transactions], 200);
    }


    /**
     * Get transactions for the authenticated merchant.
     */
    public function merchantTransactions()
    {
        $user = Auth::user();

        // Ensure the role is 'merchant'
        if ($user->role !== 'merchant') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        $transactions = Transaction::whereHas('products', function ($query) use ($user) {
            $query->where('merchant_id', $user->id);
        })->with('user', 'products')->get();

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully.',
            'data' => $transactions,
        ]);
    }

    public function viewCustomers()
    {
        $merchantId = Auth::id(); // ID merchant yang login

        $customers = Product::with(['transactionDetails.transaction.customer'])
            ->where('user_id', $merchantId) // Filter produk milik merchant
            ->whereHas('transactionDetails.transaction') // Cek apakah ada transaksi
            ->get()
            ->pluck('transactionDetails') // Ambil detail transaksi
            ->flatten() // Gabungkan menjadi satu list
            ->pluck('transaction.customer') // Ambil customer dari transaksi
            ->unique('id') // Hilangkan duplikasi berdasarkan ID customer
            ->values(); // Reset index

        return response()->json([
            'success' => true,
            'message' => 'List of customers who purchased your products',
            'data' => $customers
        ], 200);
    }
    
}