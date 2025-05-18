<?php

namespace App\Http\Controllers;

use Ap\Services\PromoService;
use App\Exceptions\ErrorException;
use App\Http\Requests\CreatePromo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    protected $promoService;

    public function __construct(PromoService $promoService)
    {
        $this->promoService = $promoService;
    }


    public function create(CreatePromo $request)
    {
        try {
            $data = $request->validated();
            $result = $this->promoService->createPromo($data);
            return response()->json($result, 201);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function getAll()
    {
        return $this->promoService->getPromos();
    }

    public function getOne(Request $request, $id)
    {
        try {
            $result = $this->promoService->getPromo($id);
            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $result = $this->promoService->updatePromo($id, $data);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $result = $this->promoService->deletePromo($id);
            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }
}
