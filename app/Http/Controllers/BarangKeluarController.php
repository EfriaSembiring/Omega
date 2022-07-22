<?php

namespace App\Http\Controllers;

use App\Models\{BarangKeluar, Barang, Laporan, Proyek};
use Illuminate\Http\Request;
use App\Http\Requests\BarangKeluarRequest;

class BarangKeluarController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:barang-keluar', [
            'only' => ['index','store', 'info', 'update', 'destroy']
        ]);
    }

    public function index(BarangKeluar $barang_keluar)
    {
        $barang = Barang::select('nama', 'id')->get();
        $proyek = Proyek::select('nama_proyek', 'id')->get();
        $data = $barang_keluar->with('barang', 'proyek')->latest()->get();
        return view('admin.barang-keluar.index', compact('data', 'barang', 'proyek'));
    }

    public function store(BarangKeluar $barang_keluar, BarangKeluarRequest $request)
    {
        $result = $barang_keluar->create($request->all());

        Barang::find($request->barang_id)->decrement('jumlah', $request->jumlah);
        
        

        // untuk laporan
        // Laporan::create([
        //     'nama' => $result->barang->nama,
        //     'orang' => $result->proyek->nama_proyek,
        //     'jumlah' => $request->jumlah,
        //     // 'berat' => $request->berat,
        //     // 'harga' => $request->harga,
        //     'jenis' => 'Barang Keluar',
        //     'root_id' => $result->id
        // ]);

        return back()->with('success', 'Stok berhasil dikurangi');
    }

    public function info(BarangKeluar $barang_keluar)
    {
        $data = $barang_keluar->with('barang', 'proyek')->find(request('id'));
        


        return $data;
    }

    public function update(BarangKeluar $barang_keluar, BarangKeluarRequest $request)
    {
        $result = $barang_keluar->find($request->id);

        $jumlah =  $result->jumlah - $request->jumlah;

        $result->update($request->all());

        // untuk laporan
        Laporan::where('jenis', 'Barang Keluar')->where('root_id', $result->id)->update([
            'nama' => $result->barang->nama,
            'orang' => $result->proyek->nama_proyek,
            'jumlah' => $request->jumlah,
            // 'berat' => $request->berat,
            'harga' => $request->harga,
            'jenis' => 'Barang Keluar'
        ]);

        // untuk barang
        $result->barang->increment('jumlah', $jumlah);

        return back()->with('success', 'Stok berhasil di update');
    }

    public function destroy(BarangKeluar $barang_keluar, $id)
    {
        $data = $barang_keluar->find($id);
        $data->barang->increment('jumlah', $data->jumlah);
        Laporan::where('jenis', 'Barang Keluar')->where('root_id', $data->id)->delete();
        $data->delete();

        return back();
    }
}
