<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product'])->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => 0,
                'paid' => $validated['paid'],
                'due' => 0,
                'status' => 'pending',
            ]);

            $total = 0;

            foreach ($validated['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);
                $quantity = $productData['quantity'];

                if ($product->stock < $quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                $product->decrement('stock', $quantity);

                $total += $product->price * $quantity;
            }

            $order->update([
                'total' => $total,
                'due' => max(0, $total - $validated['paid']),
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

    public function pendingOrders()
    {
        $orders = Order::where('status', 'pending')
                       ->with(['customer', 'orderItems.product'])
                       ->latest()
                       ->paginate(10);
        return view('orders.pending', compact('orders'));
    }

    public function completeOrders()
    {
        $orders = Order::where('status', 'completed')
                       ->with(['customer', 'orderItems.product'])
                       ->latest()
                       ->paginate(10);
        return view('orders.completed', compact('orders'));
    }

    public function pendingDue()
    {
        $orders = Order::where('due', '>', 0)
                       ->with(['customer', 'orderItems.product'])
                       ->latest()
                       ->paginate(10);
        return view('orders.pending_due', compact('orders'));
    }

    public function markAsCompleted(Order $order)
    {
        $order->update(['status' => 'completed']);
        return redirect()->route('orders.show', $order)->with('success', 'Order marked as completed.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'additional_payment' => 'required|numeric|min:0',
        ]);

        $newPaid = $order->paid + $validated['additional_payment'];
        $newDue = max(0, $order->total - $newPaid);

        $order->update([
            'paid' => $newPaid,
            'due' => $newDue,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Payment updated successfully.');
    }
}