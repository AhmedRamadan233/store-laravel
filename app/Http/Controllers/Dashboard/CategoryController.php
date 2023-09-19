<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ImageProcessing;
class CategoryController extends Controller
{
    use ImageProcessing;
    /**
     * Display a listing of the resource.
     */
    public function getAllCategories()
    {
        $categories = Category::all();
        return response()->json (['All Categories' =>  $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCategory(Request $request)
    {
        $imagePath = $this->saveImage($request->file('image'));

        $category = new Category();
        $category->name = $request->post('name');
        // Set the slug before other attributes
        $category->slug = Str::slug($request->post('name'));
        $category->parent_id = $request->post('parent_id');
        $category->description = $request->post('description');
        $category->status = $request->post('status');
        $category->image = $imagePath;
        $category->save();
        $category->image_url = asset('images/' . $category->image);
        return response()->json(['message' => 'Category created successfully', 'category' => $category,'categoryImgUrl' => $category->image_url ], 201);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image) {
            $this->deleteImage($category->image); // Delete the associated image
        }
        $category->delete();
        return response()->json(['data' => 'Deleted category with ID: '.$id ,'name' => $category->name], 200);
    }
}
