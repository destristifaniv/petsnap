<?php
namespace App\Filament\Resources\ObatResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ObatResource;
use Illuminate\Routing\Router;


class ObatApiService extends ApiService
{
    protected static string | null $resource = ObatResource::class;

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
