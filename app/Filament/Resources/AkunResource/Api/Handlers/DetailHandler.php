<?php

namespace App\Filament\Resources\AkunResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\AkunResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\AkunResource\Api\Transformers\AkunTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = AkunResource::class;
    public static bool $public = true;


    /**
     * Show Akun
     *
     * @param Request $request
     * @return AkunTransformer
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

        return new AkunTransformer($query);
    }
}
