<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyekRequest;
use App\Models\Barang;
use App\Models\Proyek;
use App\Models\ProyekBarang;
use Carbon\Carbon;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyekController extends Controller
{
    private $database;

    function __construct()
    {
        $this->middleware('permission:proyek', [
            'only' => ['index', 'store', 'info', 'update', 'destroy']
        ]);
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function index(Proyek $proyek, ProyekBarang $proyek_barang)
    {
        $barangs = Barang::get();
        $proyeks = $proyek->get();

        $data = null;

        if (request('filter_proyek')) {
            $data = $proyek->where('nama_proyek', request('filter_proyek'))->get();
        } else {
            $data = $proyek_barang->get();
        }

        return view('admin.proyek.index', compact('data', 'barangs', 'proyeks'));
    }

    public function store(Proyek $proyek, ProyekRequest $request)
    {
        $user = Auth::user();

        // data inputan user disimpan di payload
        $payload = $request->all();
dd($payload);
        $payload['user_id'] = $user->id;

        // ambil proyek id dari proyek yang sudah ada (dari dropdown)
        $proyek_id = $payload['proyek_id'];
        $proyek_name = $payload['nama_proyek'];
        $barang = Barang::find($payload['barang_id']);

        // jika proyek diambil dari dropdown
        if ($proyek_id == $proyek_name)
        {
            $proyek_name = Proyek::find($proyek_id)->nama_proyek;
        }

        if ($proyek_id == "")
        {
            // jika proyek baru, maka simpan proyek ke tabel proyeks
            $result = $proyek->create($payload);
            $proyek_id = $result->id;
        }

        $proyek_barang_payload = [
            'proyek_id' => $proyek_id,
            'proyek_name' => $proyek_name,
            'barang_id' => $payload['barang_id'],
            'barang_name' => $barang->nama,
            'jumlah' => $payload['jumlah']
        ];

        ProyekBarang::insert($proyek_barang_payload);

        //untuk notifikasi
        $title = "Penambahan kebutuhan barang {$barang->nama} dari proyek {$proyek_name} ({$user->name})";
        
        $this->database
            ->getReference('notication/proyek/' . $proyek_id)
            ->set([
                'user' => $user->name,
                'id' => $proyek_id,
                'title' => $title,
                'isRead' => 'no',
                'nama_barang' => $barang->nama,
                'jumlah' => $payload['jumlah'],
                'created_at' => time()
            ]);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function info(Proyek $proyek)
    {
        $data = $proyek->find(request('id'));
        $barang = Barang::find($data->id);
        return [
            'proyek' => $data,
            'barang' => [
                'id' => $barang->id,
                'nama' => $barang->nama
            ]
        ];
    }


    public function update(Proyek $proyek, ProyekRequest $request)
    {
        $proyek->find($request->id)->update($request->all());

        return back();
    }

    public function destroy(Proyek $proyek, $id)
    {
        $data = $proyek->find($id);
        $data->delete();

        return back();
    }
}
