<?php

namespace App\Filament\Resources\DokterResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\DokterResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\DokterResource\Api\Transformers\DokterTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = DokterResource::class;
    public static bool $public = true;


    /**
     * Show Dokter
     *
     * @param Request $request
     * @return DokterTransformer
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

        return new DokterTransformer($query);
    }
}
