<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\CreateCardProduct;
use App\Http\Requests\CreateProduct;
use App\Http\Requests\UpdateProduct;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function create(CreateProduct $request)
    {
        try {
            $data = $request->validated();
            $image = $request->file('image');
            $result = $this->productService->createProduct($data, $image);

            return response()->json($result, 201);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function getAll()
    {
        $result = $this->productService->getProducts();
        return response()->json($result, 200);
    }

    public function getOne(Request $request, $id)
    {
        try {
            $product = $this->productService->getProduct($id);
            return response()->json($product, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function favorite(Request $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $result = $this->productService->favoriteProduct($userId, $id);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function card(CreateCardProduct $request, $id)
    {
        $data = $request->validated();
        $userId = $request->user()->id;
        $result = $this->productService->addToCart($userId, $id, $data['qty'], $data['price']);

        return response()->json($result, 200);
    }

    public function update(UpdateProduct $request, $id)
    {
        try {
            $data = $request->validated();
            $image = $request->file('image');
            $product = $this->productService->updateProduct($id, $data, $image);

            return response()->json($product, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $product = $this->productService->deleteProduct($id);
            return response()->json($product, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }
}
