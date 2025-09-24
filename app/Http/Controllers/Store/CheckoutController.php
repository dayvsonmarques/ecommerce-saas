<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('store.cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $cart->load('items.product');

        return Inertia::render('Store/Checkout/Index', [
            'cartItems' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.street' => 'required|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.state' => 'required|string|max:255',
            'shipping_address.zip' => 'required|string|max:10',
            'shipping_address.country' => 'required|string|max:255',
            'billing_address' => 'required|array',
            'billing_address.name' => 'required|string|max:255',
            'billing_address.street' => 'required|string|max:255',
            'billing_address.city' => 'required|string|max:255',
            'billing_address.state' => 'required|string|max:255',
            'billing_address.zip' => 'required|string|max:10',
            'billing_address.country' => 'required|string|max:255',
            'payment_method' => 'required|string|in:credit_card,debit_card,pix,boleto,mercadopago',
            'notes' => 'nullable|string|max:1000',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_method' => 'nullable|string|max:255',
        ]);

        $cart = $this->getCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('store.cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        try {
            DB::beginTransaction();

            // Calculate total with shipping
            $shippingCost = $request->shipping_cost ?? 0;
            $totalAmount = $cart->total_amount + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_id' => null, // For now, we'll use user_id
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order' => $order,
                    'message' => 'Pedido criado com sucesso!'
                ]);
            }

            return redirect()->route('store.checkout.success', $order->id)
                ->with('success', 'Pedido realizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao processar o pedido. Tente novamente.');
        }
    }

    public function success(Order $order)
    {
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return Inertia::render('Store/Checkout/Success', [
            'order' => $order,
        ]);
    }

    public function failure()
    {
        return Inertia::render('Store/Checkout/Failure');
    }

    public function pending()
    {
        return Inertia::render('Store/Checkout/Pending');
    }

    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with('items.product')->first();
        } else {
            $sessionId = Session::getId();
            return Cart::where('session_id', $sessionId)->with('items.product')->first();
        }
    }
}