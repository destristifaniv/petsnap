<?php
namespace App\Filament\Resources\DiagnosaResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\DiagnosaResource;
use App\Filament\Resources\DiagnosaResource\Api\Requests\CreateDiagnosaRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = DiagnosaResource::class;
    public static bool $public = true;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Diagnosa
     *
     * @param CreateDiagnosaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateDiagnosaRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}