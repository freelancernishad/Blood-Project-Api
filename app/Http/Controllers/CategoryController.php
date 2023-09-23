<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Retrieve all categories with their child categories
        $categories = Category::with('childCategories')->whereNull('parent_category_id')->get();

        return response()->json(['categories' => $categories]);
    }

    public function show($id)
    {
        // Retrieve a category by its ID with child categories
        $category = Category::with('childCategories')->findOrFail($id);

        return response()->json(['category' => $category]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories',
            'parent_category_id' => 'nullable|exists:categories,id',
        ]);

        // Create a new category
        $category = Category::create($request->all());

        return response()->json(['category' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'parent_category_id' => 'nullable|exists:categories,id',
        ]);

        // Find the category by ID
        $category = Category::findOrFail($id);

        // Update the category
        $category->update($request->all());

        return response()->json(['category' => $category]);
    }

    public function destroy($id)
    {
        // Find the category by ID
    $category = Category::findOrFail($id);

    // Retrieve all child categories of the category being deleted
    $childCategories = Category::where('parent_category_id', $id)->get();

    // Update child categories to set their parent_category_id to null
    foreach ($childCategories as $childCategory) {
        $childCategory->parent_category_id = null;
        $childCategory->save();
    }

    // Delete the category
    $category->delete();

    return response()->json(['message' => 'Category deleted successfully']);
    }

    public function ancestors($id)
    {
        // Retrieve ancestors of a category
        $category = Category::findOrFail($id);

        $ancestors = $category->ancestors;

        return response()->json(['ancestors' => $ancestors]);
    }

    public function descendants($id)
    {
        // Retrieve descendants of a category
        $category = Category::findOrFail($id);

        $descendants = $category->descendants;

        return response()->json(['descendants' => $descendants]);
    }
}
