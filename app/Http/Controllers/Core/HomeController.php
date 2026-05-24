<?php
namespace App\Http\Controllers\Core;

use App\Charts\Dashboard;
use App\Dao\Models\Barang;
use App\Dao\Models\Category;
use App\Dao\Models\Departemen;
use App\Dao\Models\Keluar;
use App\Dao\Models\KeluarDetail;
use App\Dao\Models\Masuk;
use App\Dao\Models\MasukDetail;
use App\Dao\Models\Supplier;
use App\Dao\Traits\RedirectAuth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class HomeController extends Controller
{
    use RedirectAuth;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function cms()
    {
        $secret = env('APP_KEY');

        $payload = [
            'email' => auth()->user()->email,
            'time'  => time()
        ];

        $b64 = base64_encode(json_encode($payload));

        $sig = hash_hmac('sha256', $b64, $secret);

        $token = $b64 . '.' . $sig;

        return redirect(env('WP_URL')."/wordpress-auto-login?token={$token}");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Dashboard $chart)
    {
        if (empty(auth()->user())) {
            header('Location: ' . route('public'));
        }

        // Basic counts
        $total_barang = Barang::count();
        $total_supplier = Supplier::count();
        $total_category = Category::count();
        $total_departemen = Departemen::count();
        $total_qty = Barang::sum('barang_qty');

        // Today's transactions
        $total_masuk = MasukDetail::leftJoinRelationship('has_masuk')
            ->where('masuk_tanggal', date('Y-m-d'))
            ->sum('masuk_detail_qty');
        $total_keluar = KeluarDetail::leftJoinRelationship('has_keluar')
            ->where('keluar_tanggal', date('Y-m-d'))
            ->sum('keluar_detail_qty');

        // Monthly transactions
        $monthly_masuk = MasukDetail::leftJoinRelationship('has_masuk')
            ->whereMonth('masuk_tanggal', date('m'))
            ->whereYear('masuk_tanggal', date('Y'))
            ->sum('masuk_detail_qty');
        $monthly_keluar = KeluarDetail::leftJoinRelationship('has_keluar')
            ->whereMonth('keluar_tanggal', date('m'))
            ->whereYear('keluar_tanggal', date('Y'))
            ->sum('keluar_detail_qty');

        // Transaction counts
        $total_transaksi_masuk = Masuk::whereDate('masuk_tanggal', date('Y-m-d'))->count();
        $total_transaksi_keluar = Keluar::whereDate('keluar_tanggal', date('Y-m-d'))->count();

        // Low stock items (qty < 10)
        $low_stock = Barang::where('barang_qty', '<', 10)
            ->orderBy('barang_qty', 'asc')
            ->limit(10)
            ->get();

        // Recent transactions
        $recent_masuk = Masuk::with('has_supplier')
            ->orderBy('masuk_tanggal', 'desc')
            ->limit(5)
            ->get();
        $recent_keluar = Keluar::with('has_departemen')
            ->orderBy('keluar_tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('core.home.dashboard', [
            'chart' => $chart->build(),
            'total_barang' => $total_barang,
            'total_supplier' => $total_supplier,
            'total_category' => $total_category,
            'total_departemen' => $total_departemen,
            'total_qty' => $total_qty,
            'total_masuk' => $total_masuk,
            'total_keluar' => $total_keluar,
            'monthly_masuk' => $monthly_masuk,
            'monthly_keluar' => $monthly_keluar,
            'total_transaksi_masuk' => $total_transaksi_masuk,
            'total_transaksi_keluar' => $total_transaksi_keluar,
            'low_stock' => $low_stock,
            'recent_masuk' => $recent_masuk,
            'recent_keluar' => $recent_keluar,
        ]);
    }

    public function delete($code)
    {
        $navigation = session()->get('navigation');
        if (! empty($navigation) && array_key_exists($code, $navigation)) {
            unset($navigation[$code]);
            session()->put('navigation', $navigation);
        }

        return redirect()->back();
    }

    public function console()
    {
        return LaravelWebConsole::show();
    }

    public function doc()
    {
        return view('doc');
    }

    public function error402()
    {
        return view('errors.402');
    }
}
