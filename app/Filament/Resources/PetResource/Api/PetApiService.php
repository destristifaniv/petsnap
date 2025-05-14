<?php
namespace App\Filament\Resources\PetResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\PetResource;
use Illuminate\Routing\Router;


class PetApiService extends ApiService
{
    protected static string | null $resource = PetResource::class;

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
