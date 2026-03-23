<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wishlist = $user->wishlistedProducts()->latest()->get();
        $orders = $user->orders()->latest()->with('items.product')->get();
        return view('profile', compact('user', 'wishlist', 'orders'));
    }
}
