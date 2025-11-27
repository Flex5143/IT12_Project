<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        $categories = MenuItem::distinct()->pluck('category');
        
        return view('cashier.pos', compact('menuItems', 'categories'));
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'payment_amount' => 'required|numeric|min:0',
            'order_type' => 'required|in:Dine In,Take Out',
        ]);

        $total = 0;
        $items = [];

        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['id']);
            $subtotal = $menuItem->price * $item['quantity'];
            $total += $subtotal;
            $items[] = $menuItem;
        }

        if ($request->payment_amount < $total) {
            return back()->withErrors(['payment' => 'Insufficient payment amount']);
        }

        // Create order
        $order = Order::create([
            'order_id' => 'ORD' . Str::random(8),
            'user_id' => auth()->id(),
            'total' => $total,
            'payment_amount' => $request->payment_amount,
            'change_amount' => $request->payment_amount - $total,
            'payment_method' => 'Cash',
            'order_type' => $request->order_type,
            'status' => 'pending',
        ]);

        // Create order items and update stock
        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['id']);
            
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $menuItem->price,
            ]);

            // Update stock
            $menuItem->decrement('stock', $item['quantity']);
        }

        return redirect()->route('cashier.receipt', $order->id);
    }

    public function showReceipt(Order $order)
    {
        $order->load('orderItems.menuItem');
        return view('cashier.receipt', compact('order'));
    }
}