<?php
namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('shop.index', compact('products'));
    }

    public function view($id)
    {
        $product = Product::findOrFail($id);
        return view('shop.view', compact('product'));
    }
}