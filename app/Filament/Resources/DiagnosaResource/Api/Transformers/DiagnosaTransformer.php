<?php
namespace App\Filament\Resources\DiagnosaResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Diagnosa;

/**
 * @property Diagnosa $resource
 */
class DiagnosaTransformer extends JsonResource
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
