<?php
namespace App\Filament\Resources\ObatResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Obat;

/**
 * @property Obat $resource
 */
class ObatTransformer extends JsonResource
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
