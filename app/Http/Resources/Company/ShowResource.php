<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\JsonResource;

// Rota 3: Trocaria para CompanyResource
class ShowResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        return [
            'id'   => $this->resource['id'],
            'name' => $this->resource['name'],
        ];
    }
}
