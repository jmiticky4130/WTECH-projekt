<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['shippingMethod', 'paymentMethod', 'items'])
            ->latest()
            ->paginate(10);

        return view('pages.store.orders', ['orders' => $orders]);
    }
}
