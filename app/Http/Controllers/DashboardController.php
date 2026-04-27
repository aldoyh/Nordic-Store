<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $shops = auth()->user()->shops()->with('products', 'orders')->get();
        $totalRevenue = auth()->user()->orders()->where('status', 'paid')->sum('total_price');
        $totalOrders = auth()->user()->orders()->count();

        return view('dashboard.index', compact('shops', 'totalRevenue', 'totalOrders'));
    }
}
