<?php
namespace App\Filament\Resources\AkunResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\AkunResource;
use App\Filament\Resources\AkunResource\Api\Requests\CreateAkunRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = AkunResource::class;
    public static bool $public = true;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Akun
     *
     * @param CreateAkunRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateAkunRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}