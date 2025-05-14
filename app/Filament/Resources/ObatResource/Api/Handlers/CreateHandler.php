<?php
namespace App\Filament\Resources\ObatResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ObatResource;
use App\Filament\Resources\ObatResource\Api\Requests\CreateObatRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ObatResource::class;
    public static bool $public = true;
    
    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Obat
     *
     * @param CreateObatRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateObatRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}