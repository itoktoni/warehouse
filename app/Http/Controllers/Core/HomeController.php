<?php
namespace App\Http\Controllers\Core;

use App\Charts\Dashboard;
use App\Dao\Models\Barang;
use App\Dao\Models\KeluarDetail;
use App\Dao\Models\MasukDetail;
use App\Dao\Traits\RedirectAuth;
use App\Http\Controllers\Controller;

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

        $total_barang = Barang::select(Barang::field_primary())->count();
        $total_qty = Barang::sum('barang_qty');

        $total_masuk = MasukDetail::leftJoinRelationship('has_masuk')->where('masuk_tanggal', date('Y-m-d'))->sum('masuk_detail_qty');
        $total_keluar = KeluarDetail::leftJoinRelationship('has_keluar')->where('keluar_tanggal', date('Y-m-d'))->sum('keluar_detail_qty');

        return view('core.home.dashboard', [
            'chart' => $chart->build(),
            'total_barang' => $total_barang,
            'total_qty' => $total_qty,
            'total_masuk' => $total_masuk,
            'total_keluar' => $total_keluar,
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
