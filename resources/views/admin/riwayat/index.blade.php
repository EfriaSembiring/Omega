<x-app-layout>
    <x-slot name="title">Daftar Riwayat Stok</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif

    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('admin.riwayat.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Kategori</label>
                            <select name="kategori" class="form-control">
                                <option value="">--- Pilih Kategori ---</option>
                                <option value="">Semua Kategori</option>
                                @foreach ($kategori as $row)
                                    <option value="{{ $row->id }}"
                                        {{ app('request')->input('kategori') == $row->id ? 'selected' : '' }}>
                                        {{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Barang</label>
                            <select name="barang" class="form-control">
                                <option value="">--- Pilih Barang ---</option>
                                <option value="">Semua Barang</option>
                                @foreach ($barang as $row)
                                    <option value="{{ $row->id }}"
                                        {{ app('request')->input('barang') == $row->id ? 'selected' : '' }}>
                                        {{ $row->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary mb-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-card>
        <x-slot name="title">Semua Riwayat Stok</x-slot>
        <div class="table-responsive">
            <table class="display table table-striped table-hover" id="daftar">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Barang Masuk</th>
                        <th>Barang Keluar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->masuk }}</td>
                            <td>{{ $row->keluar }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.riwayat.detail', ['date' => $row->date, 'kategori' => app('request')->input('kategori'), 'barang' => app('request')->input('barang')]) }}"
                                    class="btn btn-sm btn-info info"><i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </x-card>

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
