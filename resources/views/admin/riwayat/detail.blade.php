<x-app-layout>
    <x-slot name="title">Daftar Detail Riwayat Stok</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif

    <div class="row">
        <div class="col-12">
            <a href="{{ route('admin.riwayat.index') }}" class="btn btn-primary mb-3">Kembali</a>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold">Tanggal : {{ app('request')->input('date') }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-card>
                <x-slot name="title">Barang Masuk</x-slot>
                <div class="table-responsive">
                    <table class="display table table-striped table-hover" id="daftar">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Kategori</th>
                                <th>Nama</th>
                                <th>Stok Barang Masuk</th>
                                <th>Jumlah Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($masuk as $row)
                                <tr>
                                    <td>
                                        <img src={{ $row->barang->image ?? 'https://bitsofco.de/content/images/2018/12/broken-1.png' }}
                                            width="50" alt="">
                                    </td>
                                    <td>{{ $row->barang->kategori->nama }}</td>
                                    <td>{{ $row->barang->nama }}</td>
                                    <td>{{ $row->stok_brg_masuk }}</td>
                                    <td>{{ $row->barang->jumlah }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
        <div class="col-md-6">
            <x-card>
                <x-slot name="title">Barang Keluar</x-slot>
                <div class="table-responsive">
                    <table class="display table table-striped table-hover" id="daftar">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Kategori</th>
                                <th>Nama</th>
                                <th>Stok Barang Keluar</th>
                                <th>Jumlah Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($keluar) > 0)
                                @foreach ($keluar as $row)
                                    <tr>
                                        <td>
                                            <img src={{ $row->barang->image ?? 'https://bitsofco.de/content/images/2018/12/broken-1.png' }}
                                                width="50" alt="">
                                        </td>
                                        <td>{{ $row->barang->kategori->nama }}</td>
                                        <td>{{ $row->barang->nama }}</td>
                                        <td>{{ $row->stok_brg_keluar }}</td>
                                        <td>{{ $row->barang->jumlah }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="5" class="text-center">Data kosong</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{ asset('dist/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#daftar').DataTable();
            });
        </script>
    </x-slot>
</x-app-layout>
