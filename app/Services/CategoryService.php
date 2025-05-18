<?php

namespace App\Services;

use App\Exceptions\ErrorException;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function createCategory($data, $icon)
    {
        $isExist = Category::where('title', $data['title'])->exists();
        if ($isExist) {
            throw new ErrorException('Category already exists', 409);
        }
        $url = Storage::disk('s3')->put('category', $icon);
        $data['icon'] = $url;

        $category = Category::create($data);
        return [
            'message' => 'Category created successfully',
            'category' => new CategoryResource($category)
        ];
    }

    public function getCategories()
    {
        return CategoryResource::collection(Category::all());
    }

    public function getCategoryById($id)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            throw new ErrorException('Category not found', 404);
        }

        return new CategoryResource($category);
    }

    public function updateCategory($id, $data, $icon)
    {
        $category = $this->findById($id);

        if ($icon) {
            $url = Storage::disk('s3')->put('category', $icon);
            $data['icon'] = $url;
        }
        $category->update($data);

        return [
            'message' => 'Category updated successfully',
            'category' => new CategoryResource($category)
        ];
    }

    public function deleteCategory($id)
    {
        $category = $this->findById($id);
        $category->delete();

        return 'Category deleted successfully';
    }

    private function findById($id)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            throw new ErrorException('Category not found', 404);
        }

        return $category;
    }
}
