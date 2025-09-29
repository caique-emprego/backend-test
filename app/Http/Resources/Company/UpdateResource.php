<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\JsonResource;

// Rota 4: UpdateResource e Show Resource sao o mesmo. Trocar para CompanyResource
class UpdateResource extends JsonResource
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
