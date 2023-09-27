<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $products = Product::with(['category', 'store' , 'tags'])->filter($filters)->paginate();

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
        $products = Product::with(['tags'])->findOrFail($id);

        return response()->json(['data' => $products ]);
    }


    public function createProduct(Request $request)
    {
        $imagePath = $this->saveImage($request->file('image'));
        $product = new Product();
        $product->name = $request->input('name');
        // Set the slug before other attributes
        $product->slug = Str::slug($request->input('name'));

        $product->store_id = $request->input('store_id');
        $product->category_id = $request->input('category_id');
        $product->description = $request->input('description');
        $product->product_code = $request->input('product_code');
        $product->price = $request->input('price');
        $product->compare_price = $request->input('compare_price');
        // $product->options = $request->input('options');
        $product->rating = $request->input('rating');
        $product->features = $request->input('features');
        $product->status = $request->input('status');
        $product->image = $imagePath;
        $product->save();
        $product->image_url = asset('images/' . $product->image);
        return response()->json(['message' => 'product created successfully', 'product' => $product,'productImgUrl' => $product->image_url ], 201);
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
