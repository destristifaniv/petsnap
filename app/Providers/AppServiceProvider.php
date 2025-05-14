<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\StringType;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Schema::defaultStringLength(191);

    if (DB::getDriverName() === 'mysql') {
        // Hanya jika menggunakan MySQL dan doctrine/dbal
        $platform = DB::getDoctrineConnection()->getDatabasePlatform();

        if (!Type::hasType('enum')) {
            Type::addType('enum', StringType::class);
        }

        $platform->markDoctrineTypeCommented(Type::getType('enum'));
        $platform->registerDoctrineTypeMapping('enum', 'string');
    }
    }
}
