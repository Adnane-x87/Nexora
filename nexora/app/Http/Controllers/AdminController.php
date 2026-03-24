<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        $categoriesQuery = Category::withCount('products');
        $productsQuery = Product::with('category');

        if ($search) {
            $categoriesQuery->where('name', 'like', "%{$search}%");
            $productsQuery->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%"));
        }

        $categories = $categoriesQuery->get();
        $products = $productsQuery->latest()->get();
        $users = User::latest()->get();
        $orders = Order::with('user')->latest()->get();

        if ($request->has('activeTab')) {
            session(['activeTab' => $request->activeTab]);
        }

        $recentProducts = $products->take(5)->all();
        return view('admin.dashboard', compact('categories', 'products', 'users', 'orders', 'recentProducts'));
    }

    // --- CATEGORIES ---
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'emoji' => 'nullable|string|max:10',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);
        return back()->with('success', 'Category added!')->with('activeTab', 'categories');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category)],
            'emoji' => 'nullable|string|max:10',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);
        return back()->with('success', 'Category updated!')->with('activeTab', 'categories');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted!')->with('activeTab', 'categories');
    }

    // --- PRODUCTS ---
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'badge' => 'nullable|in:new,sale,hot',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);
        return back()->with('success', 'Product added!')->with('activeTab', 'products');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product)],
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'badge' => 'nullable|in:new,sale,hot',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);
        return back()->with('success', 'Product updated!')->with('activeTab', 'products');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image) {
            // image is stored as a relative path like 'products/file.jpg'
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Product deleted!')->with('activeTab', 'products');
    }

    // --- USERS ---
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:Admin,Editor,Viewer',
        ]);
        // Generate secure random password — admin should send credentials to user
        $validated['password'] = Hash::make(\Illuminate\Support\Str::random(16));

        User::create($validated);
        return back()->with('success', 'User added! A secure password was generated. Send the reset link to the user.')->with('activeTab', 'users');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => 'required|in:Admin,Editor,Viewer',
        ]);

        $user->update($validated);
        return back()->with('success', 'User updated!')->with('activeTab', 'users');
    }

    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete yourself!'])->with('activeTab', 'users');
        }
        $user->delete();
        return back()->with('success', 'User deleted!')->with('activeTab', 'users');
    }
    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,cancelled',
        ]);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated!');
    }
}
