<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:riwayat', [
            'only' => ['index', 'store', 'info', 'update', 'destroy']
        ]);
    }

    public function index(Request $request)
    {
        $barang = Barang::get();
        $kategori = Kategori::get();

        $query_masuk = BarangMasuk::select(DB::raw('DATE(updated_at) as date'), DB::raw('SUM(jumlah) as masuk'))
            ->groupBy('date')
            ->orderByDesc('date');
        if ($request->barang) {
            $query_masuk->where('barang_id', $request->barang);
        }
        if ($request->kategori) {
            $query_masuk->with('barang')->whereHas('barang', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $masuk = $query_masuk->get();

        $query_keluar = BarangKeluar::select(DB::raw('DATE(updated_at) as date'), DB::raw('SUM(jumlah) as keluar'))
            ->groupBy('date')
            ->orderByDesc('date');
        if ($request->barang) {
            $query_keluar->where('barang_id', $request->barang);
        }
        if ($request->kategori) {
            $query_masuk->with('barang')->whereHas('barang', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $keluar = $query_keluar->get();

        $collection = new Collection();
        $collection = $collection->concat($masuk);
        $collection = $collection->concat($keluar);
        $result = collect($collection)->groupBy('date');
        $data = [];
        foreach ($result as $key => $value) {
            $m = 0;
            $k = 0;
            if ((isset($value[0]->masuk))) {
                $m = $value[0]->masuk;
            }
            if ((isset($value[0]->keluar))) {
                $k = $value[0]->keluar;
            }
            if ((isset($value[1]->masuk))) {
                $m = $value[1]->masuk;
            }
            if ((isset($value[1]->keluar))) {
                $k = $value[1]->keluar;
            }

            $data[] = (object) [
                'date' => $value[0]->date,
                'masuk' => $m,
                'keluar' => $k,
            ];
        }
        return view('admin.riwayat.index', compact('data', 'barang', 'kategori'));
    }

    public function detail(Request $request)
    {
        $query_masuk = BarangMasuk::select('barang_id', DB::raw('SUM(jumlah) as stok_brg_masuk'))->with('barang.kategori')->whereDate('updated_at', $request->date)->groupBy('barang_id');
        if ($request->barang) {
            $query_masuk->where('barang_id', $request->barang);
        }
        if ($request->kategori) {
            $query_masuk->with('barang')->whereHas('barang', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $masuk = $query_masuk->get();

        $query_keluar = BarangKeluar::select('barang_id', DB::raw('SUM(jumlah) as stok_brg_keluar'))->with('barang.kategori')->whereDate('updated_at', $request->date)->groupBy('barang_id');
        if ($request->barang) {
            $query_keluar->where('barang_id', $request->barang);
        }
        if ($request->kategori) {
            $query_keluar->whereHas('barang', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }
        $keluar = $query_keluar->get();

        return view('admin.riwayat.detail', compact('masuk', 'keluar'));
    }
}
