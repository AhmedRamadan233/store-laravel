<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ImageProcessing;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    use ImageProcessing;
    /**
     * Display a listing of the resource.
     */
    public function getAllCategories(Request $request)
    {
        $filters = $request->query();
        
        $categories = Category::filter($filters)->paginate();
        return response()->json (['All Categories' =>  $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCategory(Request $request)
    {
        $imagePath = $this->saveImage($request->file('image'));

        $category = new Category();
        $category->name = $request->input('name');
        // Set the slug before other attributes
        $category->slug = Str::slug($request->input('name'));
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
        $category->status = $request->input('status');
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
    public function updateCategory(Request $request, $id)
    {
    try{
        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        // Set the slug before other attributes
        $category->slug = Str::slug($request->input('name'));
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
        $category->status = $request->input('status');
    
        if ($request->hasFile('image')) {
            if ($category->image) {
                $this->deleteImage($category->image); // Delete old image
            }

            $imagePath = $this->saveImage($request->file('image')); // Save new image
            $category->image = $imagePath;
        }
        $category->save();
        $category->image_url = asset('images/' . $category->image);
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ], 200);
        }catch (QueryException $e) {
        // Handle database query exceptions
        return response()->json([
            'message' => 'Database error: ' . $e->getMessage(),
            'categoryName'=> $category,
        ], 500);
    } catch (\Exception $e) {
        // Handle other exceptions
        return response()->json([
            'message' => 'An error occurred: ' . $e->getMessage(),
            'categoryName'=> $category,
        ], 500);
    }
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
