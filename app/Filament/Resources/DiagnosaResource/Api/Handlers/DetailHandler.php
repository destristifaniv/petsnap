<?php

namespace App\Filament\Resources\DiagnosaResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\DiagnosaResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\DiagnosaResource\Api\Transformers\DiagnosaTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = DiagnosaResource::class;
    public static bool $public = true;

    /**
     * Show Diagnosa
     *
     * @param Request $request
     * @return DiagnosaTransformer
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

        return new DiagnosaTransformer($query);
    }
}
