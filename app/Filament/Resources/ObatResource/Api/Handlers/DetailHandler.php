<?php

namespace App\Filament\Resources\ObatResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ObatResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ObatResource\Api\Transformers\ObatTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ObatResource::class;
    public static bool $public = true;

    /**
     * Show Obat
     *
     * @param Request $request
     * @return ObatTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new ObatTransformer($query);
    }
}
