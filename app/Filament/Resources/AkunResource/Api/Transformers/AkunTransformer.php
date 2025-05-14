<?php
namespace App\Filament\Resources\AkunResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Akun;

/**
 * @property Akun $resource
 */
class AkunTransformer extends JsonResource
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
