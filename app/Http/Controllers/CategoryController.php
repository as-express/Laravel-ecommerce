<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\CreateCategory;
use App\Http\Requests\CreateProduct;
use App\Http\Requests\UpdateCategory;
use App\Http\Requests\UpdateProduct;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function create(CreateCategory $request)
    {
        try {
            $data = $request->validated();
            $icon = $request->file('icon');

            $category = $this->categoryService->createCategory($data, $icon);

            return response()->json($category, 201);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function getAll()
    {
        $result = $this->categoryService->getCategories();
        return response()->json($result, 200);
    }

    public function getById(Request $request, $id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return response()->json($category, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function update(UpdateCategory $request, $id)
    {
        try {
            $data = $request->validated();
            $icon = $request->file('icon');
            $category = $this->categoryService->updateCategory($id, $data, $icon);

            return response()->json($category, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $category = $this->categoryService->deleteCategory($id);
            return response()->json($category, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }
}
