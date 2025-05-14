<?php
namespace App\Filament\Resources\AkunResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\AkunResource;
use Illuminate\Routing\Router;


class AkunApiService extends ApiService
{
    protected static string | null $resource = AkunResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
