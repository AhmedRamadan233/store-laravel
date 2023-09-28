<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCode;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Traits\ImageProcessing;

class ProductController extends Controller
{
    use ImageProcessing;

    public function getAllProducts(Request $request)
    {
        $filters = $request->query();
        $products = Product::with(['category', 'store' , 'tags' , 'productCode'])->filter($filters)->paginate();

        return response()->json(['data' => $products]);
    }
    // public function getAllProducts(Request $request)
    // {
    //     $filters = $request->query();
        
    //     $products = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
    //     ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
    //     ->select('products.*', 'categories.name as category_name', 'stores.name as store_name')
    //         ->filter($filters)
    //         ->paginate();

    //     return response()->json(['data' => $products]);
    // }


    public function getProductById(Request $request , $id)
    {
        $products = Product::with(['category', 'store' , 'tags' , 'productCode'])->findOrFail($id);

        return response()->json(['data' => $products ]);
    }


    public function createProduct(Request $request)
    {
        $imagePath = $this->saveImage($request->file('image'));
    
        // Generate a unique product code
        $number = mt_rand(1000000000, 9999999999);
        while ($this->productCodeExists($number)) {
            $number = mt_rand(1000000000, 9999999999);
        }
        $currentDateTime = now()->format('d-m-Y-H:i');
    
        // Create a new product
        $product = new Product([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'store_id' => $request->input('store_id'),
            'category_id' => $request->input('category_id'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'compare_price' => $request->input('compare_price'),
            'rating' => $request->input('rating'),
            'features' => $request->input('features'),
            'status' => $request->input('status'),
            'image' => $imagePath,
            'product_code' => $number,
        ]);
    
    
        // Create and store a product code in the "product_codes" table
        $productCode = ProductCode::create([
            'name' => Str::slug($request->input('name'))."-".$currentDateTime,
            'product_code' => $number,
        ]);
    
        $product->productCode()->associate($productCode);
        $product->save();

        $product->image_url = asset('images/' . $product->image);
    
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
            'productImgUrl' => $product->image_url,
            'productCode' => $productCode,
        ], 201);
    }
    


    public function productCodeExists($number){
        return Product::whereProductCode($number)->exists();
    }
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'options' => 'nullable|array', // Assuming options is an array
            'rating' => 'nullable|numeric|min:0|max:5', // Assuming rating is a numeric value between 0 and 5
            'features' => 'boolean', // Assuming features is a boolean value
            'status' => 'required|in:active,draft,archived',
        ]);

        $updatedData = [
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'compare_price' => $request->input('compare_price'),
            'options' => $request->input('options'),
            'rating' => $request->input('rating'),
            'features' => $request->input('features'),
            'status' => $request->input('status'),
        ];

        if ($request->hasFile('image')) {
            if ($product->image) {
                $this->deleteImage($product->image); // Delete the old image
            }

            $imagePath = $this->saveImage($request->file('image')); // Save the new image
            $updatedData['image'] = $imagePath;
        }

        $product->update($updatedData);

        $tags = explode(',', $request->input('tags'));
        $tag_ids = [];
        foreach ($tags as $t_name) {
            $slug = Str::slug($t_name);
            $tag = Tag::where('slug', $slug)->first();
            
            if (!$tag) {
                $tag = Tag::create([
                    'name' => $t_name,
                    'slug' => $slug,
                ]);
            }
            $tag_ids[] = $tag->id;
        }
        $product->tags()->sync($tag_ids);


        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
            'product-tag' => $product->tags,
        ], 200);
    }

    


}
