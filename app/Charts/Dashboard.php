<?php

namespace App\Charts;

use App\Dao\Models\Barang;
use App\Dao\Models\Category;
use App\Dao\Models\KeluarDetail;
use App\Dao\Models\MasukDetail;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class Dashboard
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return [
            'donut' => $this->buildDonutChart(),
            'bar' => $this->buildBarChart(),
            'line' => $this->buildLineChart(),
            'category' => $this->buildCategoryChart(),
        ];
    }

    protected function buildDonutChart()
    {
        $barang = Barang::limit(7)->orderBy('barang_qty', 'desc')->pluck('barang_nama')->toArray();
        $qty = Barang::limit(7)->orderBy('barang_qty', 'desc')->pluck('barang_qty')->toArray();

        return $this->chart->donutChart()
            ->setTitle('Total Qty - 7 Barang Teratas')
            ->addData($qty)
            ->setLabels($barang)
            ->setHeight(350)
            ->setColors(['#6777ef', '#63c2de', '#39B8FF', '#326e52', '#ff6384', '#ff9f40', '#ffcd56', '#4bc0c0', '#9966ff', '#ff6384']);
    }

    protected function buildBarChart()
    {
        $labels = [];
        $masukData = [];
        $keluarData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('d M');

            $masuk = (int) (MasukDetail::leftJoinRelationship('has_masuk')
                ->where('masuk_tanggal', $date)
                ->sum('masuk_detail_qty') ?? 0);

            $keluar = (int) (KeluarDetail::leftJoinRelationship('has_keluar')
                ->where('keluar_tanggal', $date)
                ->sum('keluar_detail_qty') ?? 0);

            $masukData[] = $masuk;
            $keluarData[] = $keluar;
        }

        return $this->chart->horizontalBarChart()
            ->setTitle('Transaksi 7 Hari Terakhir')
            ->addData($masukData, 'Barang Masuk')
            ->addData($keluarData, 'Barang Keluar')
            ->setXAxis($labels)
            ->setHeight(350)
            ->setColors(['#39B8FF', '#ff6384']);
    }

    protected function buildLineChart()
    {
        $labels = [];
        $masukData = [];
        $keluarData = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create(date('Y'), $month, 1)->format('M');

            $masuk = (int) (MasukDetail::leftJoinRelationship('has_masuk')
                ->whereMonth('masuk_tanggal', $month)
                ->whereYear('masuk_tanggal', date('Y'))
                ->sum('masuk_detail_qty') ?? 0);

            $keluar = (int) (KeluarDetail::leftJoinRelationship('has_keluar')
                ->whereMonth('keluar_tanggal', $month)
                ->whereYear('keluar_tanggal', date('Y'))
                ->sum('keluar_detail_qty') ?? 0);

            $masukData[] = $masuk;
            $keluarData[] = $keluar;
        }

        return $this->chart->barChart()
            ->setTitle('Tren Bulanan ' . date('Y'))
            ->addData($masukData, 'Barang Masuk')
            ->addData($keluarData, 'Barang Keluar')
            ->setXAxis($labels)
            ->setHeight(350)
            ->setColors(['#39B8FF', '#ff6384']);
    }

    protected function buildCategoryChart()
    {
        $categories = Category::all();
        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            $labels[] = $category->category_name ?? 'Unknown';
            $sum = (int) Barang::where('barang_id_category', $category->category_id)->sum('barang_qty');
            $data[] = $sum;
        }

        if (empty($labels)) {
            $labels = ['No Data'];
            $data = [0];
        }

        return $this->chart->donutChart()
            ->setTitle('Stock per Kategori')
            ->addData($data)
            ->setLabels($labels)
            ->setHeight(350)
            ->setColors(['#6777ef', '#63c2de', '#39B8FF', '#326e52', '#ff6384', '#ff9f40', '#ffcd56', '#4bc0c0', '#9966ff', '#ff6384']);
    }
}
