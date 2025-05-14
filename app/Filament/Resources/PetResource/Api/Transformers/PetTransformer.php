<?php
namespace App\Filament\Resources\PetResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Pet;

/**
 * @property Pet $resource
 */
class PetTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
