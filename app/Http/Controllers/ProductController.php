<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
    {
           // รับค่า per_page และ search จาก request
    $perPage = $request->input('per_page', 10); // ค่าเริ่มต้นที่ 10
    $search = $request->input('search', '');

    // การค้นหาสินค้าโดยใช้ barcode หรือ name
    $products = Product::where('barcode', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->paginate($perPage); // ใช้ paginate()

    return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|unique:products',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'unit' => 'required|string',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'barcode' => 'required',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'unit' => 'required|string',
        ]);

        $product->update($validated);

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted'], 200);
    }
}
