<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response(['Data berhasil ditampilkan' => $products]);
    }

    public function show(Product $product)
    {
        $category = $product::all();
        return response(['berhasil' => $category]);
    }

    // Mencari product berdasarkan categori ID
    public function searchByCategory(Category $category)
    {
        $products = $category->products;
        return response(['data' => $products]);
    }

    public function searchByKey(Request $request)
    {
        $products = Product::where('nama_product', 'LIKE', "%$request->key%")
                            ->orwhere('des_product', 'LIKE', "%$request->key%")->get();
        return response(['data' => $products->count(), 'product' => $products]);
    }

    public function searchKey(Request $request){
        $products = Product::where('nama_product', 'LIKE', "%$request->key%")
                            ->orwhere('des_product', 'LIKE', "%$request->key%")->get();
        return response(['data' => $products->count(), 'product' => $products]);
    }
}
