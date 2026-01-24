<?php

namespace App\Charts;

use App\Dao\Models\Barang;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class Dashboard
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        $barang = Barang::limit(10)->pluck('barang_nama')->toArray();
        $qty = Barang::limit(10)->pluck('barang_qty')->toArray();

        return $this->chart->donutChart()
            ->setTitle('TOTAL QTY 10 BARANG TERATAS.')
            ->addData($qty)
            ->setLabels($barang);
    }
}
