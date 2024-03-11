<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function show(): View
    {
        $products = Product::all();

        return view('product', ['products' => $products]);
    }

    public function store(Request $request): View
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return view('product', ['products' => Product::all()])->withErrors($validator);
        }

        try {
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
            return view('product', ['products' => Product::all()]);
        } catch (\Throwable $th) {
            return view('product')->with('error', $th);
        }
    }

    public function update(Request $request, $id): View
    {
        $validator = Validator::make($request->only('increment', 'decrement'), [
            'increment' => 'integer',
            'decrement' => 'integer',
        ]);

        if ($validator->fails()) {
            return view('product', ['products' => Product::all()])->withErrors($validator);
        }

        $product = Product::find($id);
        
        if (!$product) {
            return view('product', ['products' => Product::all()])->withErrors(["errors" => "Product not found"]);
        }
        
        if ($request->increment != null) {
            $newStock = $product->stock + $request->increment;
            $product->update(['stock' => $newStock]);
            return view('product', ['products' => Product::all()]);
        }

        if ($request->decrement!= null) {
            $newStock = $product->stock - $request->decrement;
            $product->update(['stock' => $newStock]);
            return view('product', ['products' => Product::all()]);
        }
        return view('product', ['products' => Product::all()]);
    }

    public function delete($id): View
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return view('product', ['products' => Product::all()]);
    }
}
