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
    public function getAllCategories(Request $request)
    {
        $filters = $request->query();
    
        $categories = Category::with('parent')
            // leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            // ->select(['categories.*', 'parents.name as parent_name'])
            // ->orderBy('name')
            // ->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as product_count')
            ->withCount([
                'products as product_count' => function ($query) {
                    $query->where('status', '=', 'active');
                }
            ])
            ->filter($filters)
            ->paginate();
    
        return response()->json(['data' => $categories]);
    }
    // Get Category by id
    public function getCategoryById(Request $request , $id)
    {
        $categories = Category::with('parent')
            ->withCount([
                'products as product_count' => function ($query) {
                    $query->where('status', '=', 'active');
                }
            ])->findOrFail($id);

        return response()->json(['data' => $categories ]);
    }

    public function getCategoriesTrashing(Request $request)
    {
        $filters = $request->query();
    
        $categories = Category::onlyTrashed()->leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select(['categories.*', 'parents.name as parent_name'])
            ->filter($filters)
            ->paginate();
    
        return response()->json(['data' => $categories]);
    }

    public function getCategoriesRestoring(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        // Check if the category is already restored
        if (!$category->trashed()) {
            return response()->json(['error' => 'Category is already restored.'], 400);
        }
        // Restore the category
        $category->restore();
        return response()->json(['data' => 'Restored category with ID: ' . $id, 'name' => $category->name], 200);
    }
    public function deleteCategoriesForced($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        if ($category->trashed() && !$category->deleted_at) {
            return response()->json(['error' => 'Category is already soft-deleted.'], 400);
        }
        if ($category->image) {
            $this->deleteImage($category->image); // Delete the associated image
        }
        // Force delete the category
        $category->forceDelete();
        return response()->json(['data' => 'Force deleted category with ID: '.$id ,'name' => $category->name], 200);
    }

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
        return response()->json([
            'data' =>[
                'message' => 'Category created successfully', 
                'category' => $category,
                'categoryImgUrl' => $category->image_url 
            ]
        ], 201);
    }
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $updatedData = [
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'parent_id' => $request->input('parent_id'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ];
    
        if ($request->hasFile('image')) {
            if ($category->image) {
                $this->deleteImage($category->image); // Delete old image
            }
    
            $imagePath = $this->saveImage($request->file('image')); // Save new image
            $updatedData['image'] = $imagePath;
        }
    
        $category->update($updatedData);
    
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ], 200);
    }
    
    
    
    

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        // if ($category->image) {
        //     $this->deleteImage($category->image); // Delete the associated image
        // }
        $category->delete();
        return response()->json(['data' => 'Deleted category with ID: '.$id ,'name' => $category->name], 200);
    }
}
