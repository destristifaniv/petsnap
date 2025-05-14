<?php

namespace App\Filament\Widgets;

use App\Models\Diagnosa;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PemeriksaanChart extends ChartWidget
{
    protected static ?string $heading = 'Pemeriksaan per Bulan';

    protected function getData(): array
    {
        $data = Diagnosa::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pemeriksaan',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => '#4f46e5',
                ],
            ],
            'labels' => $data->pluck('bulan')->map(function ($bulan) {
                return \Carbon\Carbon::create()->month($bulan)->format('F');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Bisa juga: line, doughnut, pie
    }
}
