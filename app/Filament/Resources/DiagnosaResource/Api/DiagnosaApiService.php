<?php
namespace App\Filament\Resources\DiagnosaResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\DiagnosaResource;
use Illuminate\Routing\Router;


class DiagnosaApiService extends ApiService
{
    protected static string | null $resource = DiagnosaResource::class;

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
