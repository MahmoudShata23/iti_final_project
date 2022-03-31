<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_id'=>$this->user_id,
            'product_id'=>$this->product_id,
            'name'=>$this->product->name,
            'image'=>$this->product->image,
            'description'=>$this->product->description,
            'price'=>$this->product->selling_price,
            'discount'=>$this->product->discount_price,
            'avg_rate'=>$this->product->avgRate,
            'count'=>$this->count
        ];
    }
}
