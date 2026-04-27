<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(): View
    {
        $shops = Shop::where('is_active', true)
            ->withCount('products')
            ->paginate(12);

        return view('marketplace.index', compact('shops'));
    }
}
