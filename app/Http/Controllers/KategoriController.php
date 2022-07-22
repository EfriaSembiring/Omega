<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:kategori', [
            'only' => ['index', 'store', 'info', 'update', 'destroy']
        ]);
    }

    public function index(Kategori $kategori)
    {
        $data = $kategori->all();
        return view('admin.kategori.index', compact('data'));
    }

    public function store(Kategori $kategori, KategoriRequest $request)
    {
        $kategori->create($request->all());

        return back()->with('success', 'kategori berhasil ditambahkan');
    }

    public function info(Kategori $kategori)
    {
        $data = $kategori->find(request('id'));
        return $data;
    }

    public function update(Kategori $kategori, KategoriRequest $request)
    {
        $kategori->find($request->id)->update($request->all());

        return back();
    }

    public function destroy(Kategori $kategori, $id)
    {
        $data = $kategori->find($id);
        $data->delete();

        return back();
    }
}
