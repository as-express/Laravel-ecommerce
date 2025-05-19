<?php

namespace Ap\Services;

use App\Exceptions\ErrorException;
use App\Http\Resources\PromoResource;
use App\Models\Promo;

class PromoService
{
    public function createPromo($data)
    {
        $isExists = Promo::where("title", $data["title"])->first();
        if ($isExists) {
            throw new ErrorException('Promo already exists', 409);
        }

        $promo = Promo::create($data);
        return new PromoResource($promo);
    }

    public function getPromos()
    {
        return PromoResource::collection(Promo::all());
    }

    public function getPromo($id)
    {
        $promo = $this->getById($id);
        return new PromoResource($promo);
    }

    public function updatePromo($id, $data)
    {
        $promo = $this->getById($id);
        $promo->update($data);

        return new PromoResource($promo);
    }

    public function deletePromo($id)
    {
        $promo = $this->getById($id);
        $promo->delete();

        return true;
    }

    public function searchPromo($title)
    {
        $promo = Promo::where('title', $title)->first();
        if ($promo) {
            throw new ErrorException('Promo not found', 404);
        }

        return new PromoResource($promo);
    }

    private function getById($id)
    {
        $promo = Promo::where('id', $id)->first();
        if ($promo) {
            throw new ErrorException('Promo not found', 404);
        }

        return $promo;
    }
}
