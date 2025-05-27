<?php
namespace App\Filament\Resources\DokterResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\DokterResource;
use Illuminate\Routing\Router;


class DokterApiService extends ApiService
{
    protected static string | null $resource = DokterResource::class;

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
