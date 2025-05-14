<?php

namespace App\Filament\Widgets;

use App\Models\Akun;
use App\Models\Diagnosa;
use App\Models\Pet;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Hewan Terdaftar', Pet::count())
                ->description('Total semua hewan')
                ->color('primary'),

            Stat::make('Pemeriksaan Hari Ini', Diagnosa::whereDate('created_at', Carbon::today())->count())
                ->description('Diagnosa hari ini')
                ->color('success'),

            Stat::make('Pemeriksaan Bulan Ini', Diagnosa::whereMonth('created_at', Carbon::now()->month)->count())
                ->description('Total selama bulan ini')
                ->color('warning'),

            Stat::make('Jumlah Pemilik Hewan', Akun::where('role', 'pemilik')->count())
                ->description('Data pemilik terdaftar')
                ->color('info'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsDashboard::class,
            PemeriksaanChart::class,
        ];
    }
}
