<?php
namespace App\Filament\Resources\ObatResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ObatResource;
use App\Filament\Resources\ObatResource\Api\Requests\UpdateObatRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = ObatResource::class;
    public static bool $public = true;
    
    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Obat
     *
     * @param UpdateObatRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateObatRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}